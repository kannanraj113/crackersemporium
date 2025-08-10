<?php

namespace Drupal\commerce_currencies\Resolver;

use Drupal\commerce_currencies\CurrentCurrency;
use Drupal\commerce_currencies\Plugin\Field\FieldType\CurrenciesPriceItem;
use Drupal\commerce_price\Price;
use Drupal\commerce_price\Resolver\PriceResolverInterface;
use Drupal\commerce_product\Entity\ProductVariationInterface;
use Drupal\commerce\Context;
use Drupal\commerce\PurchasableEntityInterface;
use Drupal\Core\Routing\AdminContext;

/**
 * Multi-currency price resolver.
 * 
 * Returns the price specified for the current currency, if any.
 */
class CommerceCurrenciesResolver implements PriceResolverInterface {
  protected CurrentCurrency $currentCurrency;
  protected AdminContext $adminContext;

  public function __construct(CurrentCurrency $current_currency, AdminContext $admin_context) {
    $this->currentCurrency = $current_currency;
    $this->adminContext = $admin_context;
  }

  /**
   * {@inheritdoc}
   */
  public function resolve(PurchasableEntityInterface $entity, $quantity, Context $context): ?Price {
    if (!$entity instanceof ProductVariationInterface) {
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
        $price = $prices->toCurrencyPrice($currency);
      } elseif ($field_name === 'price') {
        // Safety for missing multi-currency field
        $price = $entity->getPrice();
      }

      // Bail out on currency mismatch, let other resolvers handle
      return ($price instanceof Price && $currency === $price->getCurrencyCode()) ? $price : NULL;
    }

    return $price;
  }
}
