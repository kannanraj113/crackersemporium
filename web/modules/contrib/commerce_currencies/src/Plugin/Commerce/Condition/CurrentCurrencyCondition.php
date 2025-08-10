<?php

namespace Drupal\commerce_currencies\Plugin\Commerce\Condition;

use Drupal\commerce_currencies\CurrentCurrency;
use Drupal\commerce\Plugin\Commerce\Condition\ConditionBase;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides the current currency condition.
 *
 * @CommerceCondition(
 *   id = "commerce_currencies_current",
 *   label = @Translation("Current currency"),
 *   category = @Translation("Currency", context = "Commerce"),
 *   entity_type = "commerce_order",
 * )
 */
class CurrentCurrencyCondition extends ConditionBase implements ContainerFactoryPluginInterface {
  protected CurrentCurrency $currentCurrency;

  public function __construct(array $configuration, $plugin_id, $plugin_definition, CurrentCurrency $current_currency) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->currentCurrency = $current_currency;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition): CurrentCurrencyCondition {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('commerce_currencies.current_currency')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'currencies' => [],
    ] + parent::defaultConfiguration();
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildConfigurationForm($form, $form_state);

    $form['currencies'] = [
      '#type' => 'commerce_entity_select',
      '#title' => $this->t('Currencies'),
      '#default_value' => $this->configuration['currencies'],
      '#target_type' => 'commerce_currency',
      '#hide_single_entity' => FALSE,
      '#multiple' => TRUE,
      '#required' => TRUE,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    parent::submitConfigurationForm($form, $form_state);

    $values = $form_state->getValue($form['#parents']);
    $this->configuration['currencies'] = $values['currencies'];
  }

  /**
   * {@inheritdoc}
   */
  public function evaluate(EntityInterface $entity) {
    return in_array($this->currentCurrency->getCurrency(), $this->configuration['currencies']);
  }
}
