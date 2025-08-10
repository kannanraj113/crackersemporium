<?php

namespace Drupal\commerce_currencies\Plugin\Field\FieldFormatter;

use Drupal\commerce_currencies\Plugin\Field\FieldType\PreferredCurrency;
use Drupal\commerce_price\Entity\Currency;
use Drupal\Core\Field\{FieldItemListInterface, FormatterBase};
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'commerce_currencies_preferred_default' formatter.
 *
 * @FieldFormatter(
 *   id = "commerce_currencies_preferred_default",
 *   label = @Translation("Currency"),
 *   field_types = {
 *     "commerce_currencies_preferred"
 *   }
 * )
 */
class PreferredCurrencyDefaultFormatter extends FormatterBase {

  public static function defaultSettings() {
    return [
      'currency_display' => 'symbol',
    ] + parent::defaultSettings();
  }

  public function settingsForm(array $form, FormStateInterface $form_state) {
    $elements = [];

    $elements['currency_display'] = [
      '#type' => 'radios',
      '#title' => $this->t('Currency display'),
      '#options' => [
        'symbol' => $this->t('Symbol (e.g. "$")'),
        'name' => $this->t('Currency name (e.g. "US Dollar")'),
        'code' => $this->t('Currency code (e.g. "USD")'),
        'both' => $this->t('Currency name and code (e.g. "US dollar (USD)")'),
      ],
      '#default_value' => $this->getSetting('currency_display'),
    ];

    return $elements;
  }

  public function settingsSummary() {
    $summary = [];

    $currency_display = $this->getSetting('currency_display');
    $currency_display_options = [
      'symbol' => $this->t('Symbol (e.g. "$")'),
      'name' => $this->t('Currency name (e.g. "US Dollar")'),
      'code' => $this->t('Currency code (e.g. "USD")'),
      'both' => $this->t('Currency name and code (e.g. "US dollar (USD)")'),
    ];
    $summary[] = $this->t('Currency display: @currency_display.', [
      '@currency_display' => $currency_display_options[$currency_display],
    ]);

    return $summary;
  }

  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];
    foreach ($items as $delta => $item) {
      /** @var PreferredCurrency $item */
      $currency = Currency::load($item->toCurrencyCode());
      switch ($this->getSetting('currency_display')) {
        case 'symbol':
          $display = $currency?->getSymbol();
          break;
        case 'name':
          $display = $currency?->getName();
          break;
        case 'code':
          $display = $currency?->getCurrencyCode();
          break;
        case 'both':
          $display = $currency?->getName() . ' (' . $currency?->getCurrencyCode() . ')';
          break;
      }

      $elements[$delta] = [
        '#type' => 'markup',
        '#markup' => $display,
      ];
    }

    return $elements;
  }
}
