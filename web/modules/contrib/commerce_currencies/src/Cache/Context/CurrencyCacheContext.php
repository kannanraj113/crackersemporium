<?php

namespace Drupal\commerce_currencies\Cache\Context;

use Drupal\commerce_currencies\CurrentCurrency;
use Drupal\Core\Cache\{CacheableMetadata, Context\CacheContextInterface};
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Defines the currency cache context, for per currency caching.
 *
 * Cache context ID: 'currency'.
 */
class CurrencyCacheContext implements CacheContextInterface {
  use StringTranslationTrait;

  protected CurrentCurrency $currentCurrency;

  public function __construct(CurrentCurrency $current_currency) {
    $this->currentCurrency = $current_currency;
  }

  public static function getLabel() {
    return t('Currency');
  }

  public function getContext() {
    return $this->currentCurrency->getCurrency();
  }

  public function getCacheableMetadata() {
    return new CacheableMetadata();
  }
}
