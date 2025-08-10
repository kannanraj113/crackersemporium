<?php

namespace Drupal\commerce_currencies\Processor;

use Drupal\commerce_order\Entity\OrderInterface;
use Drupal\commerce_order\OrderPreprocessorInterface;
use Drupal\commerce_order\OrderProcessorInterface;
use Drupal\commerce_price\Exception\CurrencyMismatchException;
use Drupal\commerce_promotion\PromotionOrderProcessor;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Messenger\MessengerTrait;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Applies promotions to orders during the order refresh process.
 * Extended only to handle the exceptions.
 *
 * @see \Drupal\commerce_promotion\PromotionOrderProcessor
 */
class SafePromotionOrderProcessor extends PromotionOrderProcessor {
  use MessengerTrait;
  use StringTranslationTrait;

  protected OrderPreprocessorInterface $originalPreprocessor;
  protected OrderProcessorInterface $originalProcessor;

  public function __construct(OrderPreprocessorInterface $original_preprocessor, OrderProcessorInterface $original_processor, EntityTypeManagerInterface $entity_type_manager, LanguageManagerInterface $language_manager) {
    $this->originalPreprocessor = $original_preprocessor;
    $this->originalProcessor = $original_processor;
    parent::__construct($entity_type_manager, $language_manager);
  }

  /**
   * {@inheritdoc}
   */
  public function preprocess(OrderInterface $order) {
    try {
      $this->originalPreprocessor->preprocess($order);
    } catch (CurrencyMismatchException | \InvalidArgumentException $e) {
      // Still a fatal error but not a WSOD, at least
      $this->messenger()->addError(t('FATAL ERROR in the configuration of a promotion! Check that you use a Current currency condition rather than an Order currency one. @error', ['@error' => $e->getMessage()]));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function process(OrderInterface $order) {
    try {
      $this->originalProcessor->process($order);
    } catch (CurrencyMismatchException | \InvalidArgumentException $e) {
      // Still a fatal error but not a WSOD, at least
      $this->messenger()->addError(t('FATAL ERROR in the configuration of a promotion! Check that you use a Current currency condition rather than an Order currency one. @error', ['@error' => $e->getMessage()]));
    }
  }
}
