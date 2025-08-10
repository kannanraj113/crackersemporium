<?php

namespace Drupal\commerce_currencies\Resolver;

use Drupal\commerce_currencies\CurrentCurrency;
use Drupal\commerce_currencies\Plugin\Field\FieldType\CurrenciesPriceItem;
use Drupal\commerce_exchanger\DefaultExchangerCalculator;
use Drupal\commerce_price\Price;
use Drupal\commerce_price\Resolver\PriceResolverInterface;
use Drupal\commerce_product\Entity\ProductVariationInterface;
use Drupal\commerce\Context;
use Drupal\commerce\PurchasableEntityInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Routing\AdminContext;

/**
 * Currency rate exchange price resolver for the Commerce Exchanger module.
 * 
 * Returns the calculated price for the current currency, if the Exchanger module is present and functional.
 */
class CommerceExchangerResolver implements PriceResolverInterface {
  protected CurrentCurrency $currentCurrency;
  protected AdminContext $adminContext;
  protected ?DefaultExchangerCalculator $exchangerCalculator = NULL;

  public function __construct(CurrentCurrency $current_currency, AdminContext $admin_context, ModuleHandlerInterface $module_handler) {
    $this->currentCurrency = $current_currency;
    $this->adminContext = $admin_context;
    if ($module_handler->moduleExists('commerce_exchanger')) {
      $this->exchangerCalculator = \Drupal::service('commerce_exchanger.calculate');
    }
  }

  /**
   * {@inheritdoc}
   */
  public function resolve(PurchasableEntityInterface $entity, $quantity, Context $context): ?Price {
    if (!$entity instanceof ProductVariationInterface || !$this->exchangerCalculator) {
      return NULL;
    }

    /** @var Price $price */
    $price = NULL;
    if (!$this->adminContext->isAdminRoute() && PHP_SAPI !== 'cli') {
      $currency = $this->currentCurrency->getCurrency();

      $field_name = $context->getData('price', 'commerce_currencies_price');
      if ($entity->hasField($field_name) && !$entity->get($field_name)->isEmpty()) {
        /** @var CurrenciesPriceItem $prices */
        $prices = $entity->get($field_name)->first();
        $price = $prices->toPrice();
      } elseif ($field_name === 'price') {
        // Safety for missing multi-currency field
        $price = $entity->getPrice();
      }

      try {
        return $this->exchangerCalculator?->priceConversion($price, $currency);
      } catch (\Throwable $e) {
        return NULL;
      }
    }

    return $price;
  }
}
