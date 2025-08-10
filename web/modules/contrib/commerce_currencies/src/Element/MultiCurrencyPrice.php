<?php

namespace Drupal\commerce_currencies\Element;

use Drupal\commerce_price\Entity\CurrencyInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element\FormElementBase;

/**
 * Provides a multi-currency price form element.
 *
 * Usage example:
 * @code
 * $form['amount'] = [
 *   '#type' => 'commerce_currencies_price',
 *   '#title' => $this->t('Amount'),
 *   '#default_value' => ['number' => '99.99', 'currency_code' => 'USD'],
 *   '#allow_negative' => FALSE,
 *   '#size' => 60,
 *   '#maxlength' => 128,
 *   '#required' => TRUE,
 * ];
 * @endcode
 *
 * @FormElement("commerce_currencies_price")
 */
class MultiCurrencyPrice extends FormElementBase {

  /**
   * {@inheritdoc}
   */
  public function getInfo() {
    $class = get_class($this);
    return [
      '#title' => '',
      '#data' => [],
      '#price_inline_errors' => \Drupal::moduleHandler()->moduleExists('inline_form_errors'),
      '#size' => 10,
      '#maxlength' => 128,
      '#default_value' => NULL,
      '#allow_negative' => FALSE,
      '#process' => [
        [$class, 'processElement'],
        [$class, 'processAjaxForm'],
      ],
      '#input' => TRUE,
      '#theme_wrappers' => ['container'],
    ];
  }

  /**
   * Builds the commerce_currencies_price form element.
   *
   * @param array $element
   *   The initial commerce_price form element.
   * @param FormStateInterface $form_state
   *   The current state of the form.
   * @param array $complete_form
   *   The complete form structure.
   *
   * @return array
   *   The built commerce_price form element.
   *
   * @throws \InvalidArgumentException
   *   Thrown when #default_value is not an instance of
   *   \Drupal\commerce_price\Price.
   */
  public static function processElement(array $element, FormStateInterface $form_state, array &$complete_form): array {
    /** @var CurrencyInterface[] $currencies */
    $currencies = \Drupal::EntityTypeManager()
      ->getStorage('commerce_currency')
      ->loadByProperties(['status' => TRUE]);
    $currency_codes = array_keys($currencies);
    if (!empty($currency_codes)) {
      $element['#tree'] = TRUE;
      $element['#attributes']['class'][] = 'form-type-commerce-currencies-price';

      $last_code = '';
      $i = 0;
      foreach ($currency_codes as $currency_code) {
        $element[$currency_code] = [
          '#type' => 'textfield',
          '#title' => ($i == 0) ? $element['#title'] : NULL,
          '#value' => $element['#data'][$currency_code] ?? NULL,
          '#default_value' => $element['#default_value'] ? $element['#default_value']['number'] : NULL,
          '#required' => $element['#required'],
          '#size' => $element['#size'],
          '#maxlength' => $element['#maxlength'],
          '#field_suffix' => $currency_code,
          '#min_fraction_digits' => $currencies[$currency_code]->getFractionDigits(),
          '#max_fraction_digits' => 6,
          '#min' => $element['#allow_negative'] ? NULL : 0,
          '#step' => 0.0001,
        ];
        if (isset($element['#ajax'])) {
          $element[$currency_code]['#ajax'] = $element['#ajax'];
        }
        $last_code = $currency_code;
        $i++;
      }

      if (!empty($element['#description'])) {
        $element[$last_code]['#description'] = $element['#description'];
      }

      // Remove the keys that were transferred to child elements.
      unset($element['#size'], $element['#maxlength'], $element['#ajax'], $element['#description']);
    }

    return $element;
  }
}
