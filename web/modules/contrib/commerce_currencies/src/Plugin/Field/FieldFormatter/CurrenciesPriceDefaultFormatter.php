<?php

namespace Drupal\commerce_currencies\Plugin\Field\FieldFormatter;

use CommerceGuys\Intl\Formatter\CurrencyFormatterInterface;
use Drupal\commerce_currencies\Plugin\Field\FieldType\CurrenciesPriceItem;
use Drupal\commerce_price\Resolver\ChainPriceResolverInterface;
use Drupal\commerce_store\CurrentStoreInterface;
use Drupal\commerce\Context;
use Drupal\Core\Field\{FieldDefinitionInterface, FieldItemListInterface, FormatterBase};
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Plugin implementation of the 'commerce_currencies_price_default' formatter.
 *
 * @FieldFormatter(
 *   id = "commerce_currencies_price_default",
 *   label = @Translation("Multi-currency"),
 *   field_types = {
 *     "commerce_currencies_price"
 *   }
 * )
 */
class CurrenciesPriceDefaultFormatter extends FormatterBase implements ContainerFactoryPluginInterface {
  protected AccountInterface $currentUser;
  protected CurrentStoreInterface $currentStore;
  protected CurrencyFormatterInterface $currencyFormatter;
  protected ChainPriceResolverInterface $chainPriceResolver;

  public function __construct($plugin_id, $plugin_definition, FieldDefinitionInterface $field_definition, array $settings, $label, $view_mode, array $third_party_settings, AccountInterface $current_user, CurrentStoreInterface $current_store, CurrencyFormatterInterface $currency_formatter, ChainPriceResolverInterface $chain_price_resolver) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $label, $view_mode, $third_party_settings);
    $this->currentUser = $current_user;
    $this->currentStore = $current_store;
    $this->currencyFormatter = $currency_formatter;
    $this->chainPriceResolver = $chain_price_resolver;
  }

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition): CurrenciesPriceDefaultFormatter {
    return new static(
      $plugin_id,
      $plugin_definition,
      $configuration['field_definition'],
      $configuration['settings'],
      $configuration['label'],
      $configuration['view_mode'],
      $configuration['third_party_settings'],
      $container->get('current_user'),
      $container->get('commerce_store.current_store'),
      $container->get('commerce_price.currency_formatter'),
      $container->get('commerce_price.chain_price_resolver')
    );
  }

  public static function defaultSettings() {
    return [
      'strip_trailing_zeroes' => FALSE,
      'show_all_currencies' => TRUE,
      'missing_price' => 'default',
      'currency_display' => 'symbol',
    ] + parent::defaultSettings();
  }

  public function settingsForm(array $form, FormStateInterface $form_state) {
    $elements = [];

    $elements['strip_trailing_zeroes'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Strip trailing zeroes after the decimal point'),
      '#default_value' => $this->getSetting('strip_trailing_zeroes'),
    ];
    $elements['show_all_currencies'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Show all currencies'),
      '#default_value' => $this->getSetting('show_all_currencies'),
    ];
    $checkbox_parents = array_merge([$this->fieldDefinition->getName(), 'settings_edit_form', 'settings', 'show_all_currencies']);
    $checkbox_path = 'fields[' . implode('][', $checkbox_parents) . ']';
    $elements['missing_price'] = [
      '#type' => 'radios',
      '#title' => $this->t('When price is missing'),
      '#options' => [
        'default' => $this->t('Show store default currency'),
        'resolve' => $this->t('Call resolvers (eg. exchanger)'),
        'any' => $this->t('Show any price'),
        'dash' => $this->t('Dash'),
        'empty' => $this->t('Leave empty'),
      ],
      '#default_value' => $this->getSetting('missing_price'),
      '#states' => [
        'visible' => [
          ':input[name="' . $checkbox_path . '"]' => ['checked' => FALSE],
        ],
      ],
    ];
    $elements['currency_display'] = [
      '#type' => 'radios',
      '#title' => $this->t('Currency display'),
      '#options' => [
        'symbol' => $this->t('Symbol (e.g. "$")'),
        'code' => $this->t('Currency code (e.g. "USD")'),
        'none' => $this->t('None'),
      ],
      '#default_value' => $this->getSetting('currency_display'),
    ];

    return $elements;
  }

  public function settingsSummary() {
    $summary = [];

    if ($this->getSetting('show_all_currencies')) {
      $summary[] = $this->t('Show prices in all currencies.');
    } else {
      $missing_price = $this->getSetting('missing_price');
      $missing_price_options = [
        'default' => $this->t('default currency price'),
        'resolve' => $this->t('resolved price'),
        'any' => $this->t('any price'),
        'dash' => $this->t('dash'),
        'empty' => $this->t('empty'),
      ];
      $summary[] = $this->t('Only show the price in the selected currency.');
      $summary[] = $this->t('Display @missing_price when missing.', [
        '@missing_price' => $missing_price_options[$missing_price],
      ]);
    }

    if ($this->getSetting('strip_trailing_zeroes')) {
      $summary[] = $this->t('Strip trailing zeroes after the decimal point.');
    } else {
      $summary[] = $this->t('Do not strip trailing zeroes after the decimal point.');
    }

    $currency_display = $this->getSetting('currency_display');
    $currency_display_options = [
      'symbol' => $this->t('Symbol (e.g. "$")'),
      'code' => $this->t('Currency code (e.g. "USD")'),
      'none' => $this->t('None'),
    ];
    $summary[] = $this->t('Currency display: @currency_display.', [
      '@currency_display' => $currency_display_options[$currency_display],
    ]);

    return $summary;
  }

  public function viewElements(FieldItemListInterface $items, $langcode) {
    $options = $this->getFormattingOptions();
    $elements = [];
    foreach ($items as $delta => $item) {
      /** @var CurrenciesPriceItem $item */
      if ($this->getSetting('show_all_currencies')) {
        $list = [];
        $prices = $item->getValue()['prices'] ?? [];
        foreach ($prices as $currency => $number) {
          $list[$currency] = [
            '#type' => 'markup',
            '#markup' => $this->currencyFormatter->format($number, $currency, $options),
          ];
        }

        $elements[$delta] = [
          '#theme' => 'item_list',
          '#items' => $list,
        ];
      } else {
        $display = '???';
        $price = $item->toCurrentPrice();
        if ($price) {
          $display = $this->currencyFormatter->format($price->getNumber(), $price->getCurrencyCode(), $options);
        } else {
          switch ($this->getSetting('missing_price')) {
            case 'default':
              $price = $item->toDefaultPrice();
              $display = $price
                ? $this->currencyFormatter->format($price->getNumber(), $price->getCurrencyCode(), $options)
                : $this->resolvePrice($items, $options);
              break;
            case 'resolve':
              $display = $this->resolvePrice($items, $options);
              break;
            case 'any':
              $price = $item->toAnyPrice();
              $display = $price
                ? $this->currencyFormatter->format($price->getNumber(), $price->getCurrencyCode(), $options)
                : $this->resolvePrice($items, $options);
              break;
            case 'dash':
              $display = '&mdash;';
              break;
            case 'empty':
              $display = '';
              break;
          }
        }

        $elements[$delta] = [
          '#type' => 'markup',
          '#markup' => $display,
          '#cache' => [
            'contexts' => ['currency'],
          ],
        ];
      }
    }

    return $elements;
  }

  /**
   * Call the chain to get a price from any resolver (including our own).
   * 
   * @return string
   *   The formatted price. Returns '???' if there's no available price at all.
   */
  private function resolvePrice(FieldItemListInterface $items, array $options): string {
    $context = new Context($this->currentUser, $this->currentStore->getStore(), NULL, [
      'field_name' => $items->getName(),
    ]);
    $price = $this->chainPriceResolver->resolve($items->getEntity(), 1, $context);
    return $price ? $this->currencyFormatter->format($price->getNumber(), $price->getCurrencyCode(), $options) : '???';
  }

  /**
   * Gets the formatting options for the currency formatter.
   */
  protected function getFormattingOptions(): array {
    $options = [
      'currency_display' => $this->getSetting('currency_display'),
    ];
    if ($this->getSetting('strip_trailing_zeroes')) {
      $options['minimum_fraction_digits'] = 0;
    }
    return $options;
  }
}
