<?php

namespace Drupal\commerce_currencies\Processor;

use Drupal\commerce_order\Entity\OrderInterface;
use Drupal\commerce_order\OrderProcessorInterface;
use Drupal\commerce_price\Exception\CurrencyMismatchException;
use Drupal\commerce_shipping\EarlyOrderProcessor;
use Drupal\commerce_shipping\ShipmentManagerInterface;
use Drupal\commerce_shipping\ShippingOrderManagerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Messenger\MessengerTrait;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Prepares shipments for the order refresh process.
 * Extended only to handle the exceptions.
 *
 * @see \Drupal\commerce_shipping\EarlyOrderProcessor
 */
class SafeEarlyOrderProcessor extends EarlyOrderProcessor {
  use MessengerTrait;
  use StringTranslationTrait;

  protected OrderProcessorInterface $originalProcessor;

  public function __construct(OrderProcessorInterface $original_processor, EntityTypeManagerInterface $entity_type_manager, ShippingOrderManagerInterface $shipping_order_manager, ShipmentManagerInterface $shipment_manager) {
    $this->originalProcessor = $original_processor;
    parent::__construct($entity_type_manager, $shipping_order_manager, $shipment_manager);
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
