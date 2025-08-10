<?php

namespace Drupal\commerce_currencies\Processor;

use Drupal\commerce_order\Entity\OrderInterface;
use Drupal\commerce_order\OrderProcessorInterface;
use Drupal\commerce_price\Exception\CurrencyMismatchException;
use Drupal\commerce_shipping\LateOrderProcessor;
use Drupal\commerce_shipping\ShippingOrderManagerInterface;
use Drupal\Core\Messenger\MessengerTrait;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Completes the order refresh process for shipments.
 * Extended only to handle the exceptions.
 *
 * @see \Drupal\commerce_shipping\LateOrderProcessor
 */
class SafeLateOrderProcessor extends LateOrderProcessor {
  use MessengerTrait;
  use StringTranslationTrait;

  protected OrderProcessorInterface $originalProcessor;

  public function __construct(OrderProcessorInterface $original_processor, ShippingOrderManagerInterface $shipping_order_manager) {
    $this->originalProcessor = $original_processor;
    parent::__construct($shipping_order_manager);
  }

  /**
   * {@inheritdoc}
   */
  public function process(OrderInterface $order) {
    try {
      $this->originalProcessor->process($order);
    } catch (CurrencyMismatchException $e) {
      // This is not really an error
      $this->messenger()->addWarning(t('If you changed currencies, remember to press <em>Recalculate shipping methods</em> on the checkout page!'));
    } catch (\InvalidArgumentException $e) {
      // Still a fatal error but not a WSOD, at least
      $this->messenger()->addError(t('FATAL ERROR in the configuration of a shipment method! Check that you use a Current currency condition rather than an Order currency one. @error', ['@error' => $e->getMessage()]));
    }
  }
}
