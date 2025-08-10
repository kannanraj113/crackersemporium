<?php

namespace Drupal\commerce_currencies\Plugin\Field\FieldWidget;

use Drupal\commerce_price\Entity\CurrencyInterface;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\{FieldDefinitionInterface, WidgetBase, FieldItemListInterface};
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Plugin implementation of the 'commerce_currencies_preferred_default' widget.
 *
 * @FieldWidget(
 *   id = "commerce_currencies_preferred_default",
 *   label = @Translation("Currency"),
 *   field_types = {
 *     "commerce_currencies_preferred"
 *   }
 * )
 */
class PreferredCurrencyDefaultWidget extends WidgetBase {
  protected EntityStorageInterface $currencyStorage;

  public function __construct($plugin_id, $plugin_definition, FieldDefinitionInterface $field_definition, array $settings, array $third_party_settings, EntityStorageInterface $currency_storage) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $third_party_settings);
    $this->currencyStorage = $currency_storage;
  }

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition): PreferredCurrencyDefaultWidget {
    return new static(
      $plugin_id,
      $plugin_definition,
      $configuration['field_definition'],
      $configuration['settings'],
      $configuration['third_party_settings'],
      $container->get('entity_type.manager')->getStorage('commerce_currency')
    );
  }
  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    /** @var CurrencyInterface[] $currencies */
    $currencies = $this->currencyStorage->loadByProperties(['status' => TRUE]);

    $options = [];
    foreach ($currencies as $currency) {
      $options[$currency->getCurrencyCode()] = $currency->getName() . ' (' . $currency->getCurrencyCode() . ')';
    }

    $element['value'] = [
      '#type' => 'select',
      '#title' => $this->t('Preferred currency'),
      '#options' => $options,
      '#default_value' => $items[$delta]->getValue() ?? '',
    ];
    return $element;
  }
}
