<?php

namespace Drupal\commerce_currencies\Plugin\Field\FieldWidget;

use Drupal\Core\Field\{WidgetBase, FieldItemListInterface, FieldDefinitionInterface};
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'commerce_currencies_price_list' widget.
 *
 * @FieldWidget(
 *   id = "commerce_currencies_price_list",
 *   label = @Translation("List price (multi-currency)"),
 *   field_types = {
 *     "commerce_currencies_price"
 *   }
 * )
 */
class CurrenciesPriceListWidget extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $checkbox_parents = array_merge($form['#parents'], [$this->fieldDefinition->getName(), 0, 'has_value']);
    $checkbox_path = array_shift($checkbox_parents);
    $checkbox_path .= '[' . implode('][', $checkbox_parents) . ']';

    $element['has_value'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Provide a list price'),
    ];
    $values = $items->getValue()[$delta] ?? [];
    $element['value'] = [
      '#type' => 'commerce_currencies_price',
      '#title' => $this->fieldDefinition->getLabel(),
      '#data' => $values['prices'] ?? [],
      '#allow_negative' => $this->getFieldSetting('allow_negative'),
      '#states' => [
        'visible' => [
          ':input[name="' . $checkbox_path . '"]' => ['checked' => TRUE],
        ],
      ],
    ];
    if (!$items[$delta]->isEmpty()) {
      $element['has_value']['#default_value'] = TRUE;
    }
    // Remove the checkbox if the list_price field is required.
    if ($element['#required']) {
      $element['has_value']['#access'] = FALSE;
      $element['has_value']['#default_value'] = TRUE;
      $element['value']['#required'] = TRUE;
    }

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function massageFormValues(array $values, array $form, FormStateInterface $form_state) {
    foreach ($values as &$item) {
      $item = ($item['has_value'] === 1) ? $item['value'] : NULL;
    }
    return $values;
  }

  /**
   * {@inheritdoc}
   */
  public static function isApplicable(FieldDefinitionInterface $field_definition) {
    return $field_definition->getName() == 'commerce_currencies_list_price';
  }
}
