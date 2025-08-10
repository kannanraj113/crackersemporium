<?php

namespace Drupal\commerce_currencies\Processor;

use Drupal\commerce_order\Entity\OrderInterface;
use Drupal\commerce_order\OrderProcessorInterface;
use Drupal\commerce_price\Exception\CurrencyMismatchException;
use Drupal\commerce_price\RounderInterface;
use Drupal\commerce_tax\StoreTaxInterface;
use Drupal\commerce_tax\TaxOrderProcessor;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Messenger\MessengerTrait;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Applies taxes to orders during the order refresh process.
 * Extended only to handle the exceptions.
 *
 * @see \Drupal\commerce_tax\TaxOrderProcessor
 */
class SafeTaxOrderProcessor extends TaxOrderProcessor {
  use MessengerTrait;
  use StringTranslationTrait;

  protected OrderProcessorInterface $originalProcessor;

  public function __construct(OrderProcessorInterface $original_processor, EntityTypeManagerInterface $entity_type_manager, RounderInterface $rounder, StoreTaxInterface $store_tax) {
    $this->originalProcessor = $original_processor;
    parent::__construct($entity_type_manager, $rounder, $store_tax);
  }

  /**
   * {@inheritdoc}
   */
  public function process(OrderInterface $order) {
    try {
      $this->originalProcessor->process($order);
    } catch (CurrencyMismatchException | \InvalidArgumentException $e) {
      // Still a fatal error but not a WSOD, at least
      $this->messenger()->addError(t('FATAL ERROR in the configuration of a tax type! Check that you use a Current currency condition rather than an Order currency one. @error', ['@error' => $e->getMessage()]));
    }
  }
}
