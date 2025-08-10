<?php

namespace Drupal\commerce_currencies\Processor;

use Drupal\commerce_currencies\CurrentCurrency;
use Drupal\commerce_exchanger\DefaultExchangerCalculator;
use Drupal\commerce_order\Entity\{OrderInterface, OrderItem};
use Drupal\commerce_order\OrderProcessorInterface;
use Drupal\commerce_price\Price;
use Drupal\commerce_price\Resolver\ChainPriceResolverInterface;
use Drupal\commerce\Context;
use Drupal\commerce\PurchasableEntityInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Messenger\MessengerTrait;
use Drupal\Core\Routing\AdminContext;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Order processor to unify currencies.
 */
class CommerceCurrenciesOrderProcessor implements OrderProcessorInterface {
  use MessengerTrait;
  use StringTranslationTrait;

  protected CurrentCurrency $currentCurrency;
  protected AdminContext $adminContext;
  protected AccountInterface $currentUser;
  protected ChainPriceResolverInterface $chainPriceResolver;
  protected ?DefaultExchangerCalculator $exchangerCalculator = NULL;

  public function __construct(CurrentCurrency $currency, AdminContext $admin_context, AccountInterface $current_user, ChainPriceResolverInterface $chain_price_resolver, ModuleHandlerInterface $module_handler) {
    $this->currentCurrency = $currency;
    $this->adminContext = $admin_context;
    $this->currentUser = $current_user;
    $this->chainPriceResolver = $chain_price_resolver;
    if ($module_handler->moduleExists('commerce_exchanger')) {
      $this->exchangerCalculator = \Drupal::service('commerce_exchanger.calculate');
    }
  }

  /**
   * Commerce only can handle an order in a single currency (Order::recalculateTotalPrice() throws on mixed currencies).
   * We unify the currency to the current one, using the chain of resolvers to get an appropriate price.
   * 
   * Instead of making any automated (eg. rate based) calculations, it's best to leave it to the original plugins
   * to re-calculate all adjustments with a different currency in mind. Well-behaving modules (eg. Commerce Tax, Promotion, Shipping)
   * have order processors to do exactly this.
   * 
   * It's also recommended practice to not rely on currency rate conversions but to provide separate promotions, taxes,
   * shipping methods, etc. per currency, wherever needed. IMPORTANT: use our own Current currency condition,
   * not the stock Order currency one!
   */
  public function process(OrderInterface $order) {
    if (!$this->adminContext->isAdminRoute() && $order->getCustomerId() == $this->currentUser->id() && $order->getState()->value == 'draft' && PHP_SAPI !== 'cli') {
      $currency = $this->currentCurrency->getCurrency();
      foreach ($order->getItems() as $item) {
        /** @var OrderItem $item */
        if ($item->hasPurchasedEntity()) {
          $item->setUnitPrice($this->resolvePrice($order, $item->getPurchasedEntity()));
        } else {
          $item->setUnitPrice($this->convertPrice($order, $item->getUnitPrice(), $currency));
        }
      }

      $order->recalculateTotalPrice();
    }
  }

  /**
   * Call the chain to get a price from any resolver (including our own).
   */
  private function resolvePrice(OrderInterface $order, PurchasableEntityInterface $item): ?Price {
    $context = new Context($order->getCustomer(), $order->getStore(), NULL, [
      'field_name' => 'price',
    ]);
    return $this->chainPriceResolver->resolve($item, 1, $context);
  }

  /**
   * Plain prices (items without purchasable entity) are not supported by resolvers.
   */
  private function convertPrice(OrderInterface $order, Price $price, string $currency): Price {
    if ($this->exchangerCalculator != NULL) {
      try {
        return $this->exchangerCalculator->priceConversion($price, $currency);
      } catch (\Throwable $e) {
      }
    }

    //TODO:
    $this->messenger()->addWarning(t('Price reinterpreted as @currency', ['@currency' => $currency]));
    return new Price($price->getNumber(), $currency);
  }
}
