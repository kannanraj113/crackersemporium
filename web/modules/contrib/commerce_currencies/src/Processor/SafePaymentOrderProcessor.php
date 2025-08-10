<?php

namespace Drupal\commerce_currencies\Processor;

use Drupal\commerce_order\Entity\OrderInterface;
use Drupal\commerce_order\OrderProcessorInterface;
use Drupal\commerce_payment\PaymentOrderProcessor;
use Drupal\commerce_payment\PaymentOrderUpdaterInterface;
use Drupal\commerce_price\Exception\CurrencyMismatchException;
use Drupal\Core\Messenger\MessengerTrait;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Recalculates the order's total_paid field.
 * Extended only to handle the exceptions.
 *
 * @see \Drupal\commerce_payment\PaymentOrderProcessor
 */
class SafePaymentOrderProcessor extends PaymentOrderProcessor {
  use MessengerTrait;
  use StringTranslationTrait;

  protected OrderProcessorInterface $originalProcessor;

  public function __construct(OrderProcessorInterface $original_processor, PaymentOrderUpdaterInterface $payment_order_updater) {
    $this->originalProcessor = $original_processor;
    parent::__construct($payment_order_updater);
  }

  /**
   * {@inheritdoc}
   */
  public function process(OrderInterface $order) {
    try {
      $this->originalProcessor->process($order);
    } catch (CurrencyMismatchException | \InvalidArgumentException $e) {
      // Still a fatal error but not a WSOD, at least
      $this->messenger()->addError(t('FATAL ERROR in the configuration of a payment method! Check that you use a Current currency condition rather than an Order currency one. @error', ['@error' => $e->getMessage()]));
    }
  }
}
