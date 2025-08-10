<?php

namespace Drupal\commerce_currencies\Plugin\Field\FieldWidget;

use Drupal\Core\Field\{WidgetBase, FieldItemListInterface};
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'commerce_currencies_price_default' widget.
 *
 * @FieldWidget(
 *   id = "commerce_currencies_price_default",
 *   label = @Translation("Price (multi-currency)"),
 *   field_types = {
 *     "commerce_currencies_price"
 *   }
 * )
 */
class CurrenciesPriceDefaultWidget extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $values = $items->getValue()[$delta] ?? [];
    return [
      '#type' => 'commerce_currencies_price',
      '#title' => $this->fieldDefinition->getLabel(),
      '#data' => $values['prices'] ?? [],
      '#allow_negative' => $this->getFieldSetting('allow_negative'),
    ] + $element;
  }
}
