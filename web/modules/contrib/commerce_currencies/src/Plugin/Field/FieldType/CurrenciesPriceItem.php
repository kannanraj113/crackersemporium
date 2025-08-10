<?php

namespace Drupal\commerce_currencies\Plugin\Field\FieldType;

use Drupal\commerce_price\Price;
use Drupal\Core\Field\{FieldItemBase, FieldStorageDefinitionInterface};
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\TypedData\{MapDataDefinition, Exception\MissingDataException};
use InvalidArgumentException;

/**
 * Plugin implementation of the 'commerce_currencies_price' field type.
 *
 * @FieldType(
 *   id = "commerce_currencies_price",
 *   label = @Translation("Price (multi-currency)"),
 *   description = @Translation("Stores multi-currency prices."),
 *   category = "commerce",
 *   default_widget = "commerce_currencies_price_default",
 *   default_formatter = "commerce_currencies_price_default",
 * )
 */
class CurrenciesPriceItem extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public static function mainPropertyName(): ?string {
    return 'prices';
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition): array {
    return [
      'columns' => [
        'prices' => [
          'description' => 'Serialized',
          'type' => 'blob',
          'size' => 'normal',
          'serialize' => TRUE,
        ],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition): array {
    $properties['prices'] = MapDataDefinition::create()
      ->setLabel(t('Prices data'));

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultFieldSettings() {
    return [
      'allow_negative' => FALSE,
    ] + parent::defaultFieldSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function fieldSettingsForm(array $form, FormStateInterface $form_state) {
    $element = [];

    $element['allow_negative'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Allow negative prices'),
      '#default_value' => $this->getSetting('allow_negative'),
    ];

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function setValue($values, $notify = TRUE) {
    $prices = NULL;
    if (isset($values) && is_array($values)) {
      foreach ($values as $key => $value) {
        if (!str_starts_with($key, '_') && !empty($value)) {
          $prices['prices'][$key] = doubleval($value);
        }
      }
    }
    parent::setValue($prices, $notify);
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty(): bool {
    try {
      $value = $this->getValue();
      return !is_array($value) || empty($value);
    } catch (MissingDataException | InvalidArgumentException $e) {
      return FALSE;
    }
  }

  /**
   * Gets the best possible price.
   */
  public function toPrice(): ?Price {
    return $this->toCurrentPrice() ?? $this->toDefaultPrice() ?? $this->toAnyPrice();
  }

  /**
   * Gets the price for the current currency.
   */
  public function toCurrentPrice(): ?Price {
    $currency = \Drupal::service('commerce_currencies.current_currency')->getCurrency() ?? '';
    $amount = $this->prices[$currency] ?? NULL;
    return $amount ? new Price($amount, $currency) : NULL;
  }

  /**
   * Gets the price for the store default currency.
   */
  public function toDefaultPrice(): ?Price {
    $currency = \Drupal::service('commerce_store.current_store')->getStore()?->getDefaultCurrencyCode() ?? '';
    $amount = $this->prices[$currency] ?? NULL;
    return $amount ? new Price($amount, $currency) : NULL;
  }

  /**
   * Gets the price for the first available currency.
   */
  public function toAnyPrice(): ?Price {
    foreach ($this->prices as $currency => $amount) {
      return new Price($amount, $currency);
    }
    return NULL;
  }

  /**
   * Gets the price for a specific currency.
   */
  public function toCurrencyPrice(string $currency): ?Price {
    $amount = $this->prices[$currency] ?? NULL;
    return $amount ? new Price($amount, $currency) : NULL;
  }
}
