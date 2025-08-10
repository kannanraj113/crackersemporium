<?php

namespace Drupal\commerce_currencies\Plugin\Field\FieldType;

use Drupal\Core\Field\{FieldItemBase, FieldStorageDefinitionInterface};
use Drupal\Core\TypedData\{DataDefinition, Exception\MissingDataException};
use InvalidArgumentException;

/**
 * Plugin implementation of the 'commerce_currencies_price' field type.
 *
 * @FieldType(
 *   id = "commerce_currencies_preferred",
 *   label = @Translation("Preferred currency"),
 *   description = @Translation("Stores the preferred currency of the user."),
 *   category = "commerce",
 *   default_widget = "commerce_currencies_preferred_default",
 *   default_formatter = "commerce_currencies_preferred_default",
 * )
 */
class PreferredCurrency extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public static function mainPropertyName(): ?string {
    return 'value';
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition): array {
    return [
      'columns' => [
        'value' => [
          'description' => 'The currency code.',
          'type' => 'varchar',
          'length' => 3,
        ],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition): array {
    $properties['value'] = DataDefinition::create('string')
      ->setLabel(t('Currency code'))
      ->setRequired(FALSE);

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty(): bool {
    try {
      return $this->value === NULL || $this->value === '';
    } catch (MissingDataException | InvalidArgumentException $e) {
      return FALSE;
    }
  }

  /**
   * Gets the preferred currency.
   */
  public function toCurrencyCode(): string {
    return $this->value ?? '';
  }
}
