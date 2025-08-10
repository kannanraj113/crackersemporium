<?php

namespace Drupal\commerce_currencies\Plugin\Block;

use Drupal\commerce_currencies\Form\CurrencySelectorForm;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\{FormBuilderInterface, FormStateInterface};
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Routing\CurrentRouteMatch;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a currency selector block.
 *
 * @Block(
 *   id = "commerce_currencies_selector",
 *   admin_label = @Translation("Currency selector"),
 *   category = @Translation("Commerce")
 * )
 */
class CurrencySelectorBlock extends BlockBase implements ContainerFactoryPluginInterface {
  protected FormBuilderInterface $formBuilder;
  protected CurrentRouteMatch $currentRouteMatch;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, FormBuilderInterface $form_builder, CurrentRouteMatch $current_route_match) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->formBuilder = $form_builder;
    $this->currentRouteMatch = $current_route_match;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition): CurrencySelectorBlock {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('form_builder'),
      $container->get('current_route_match')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'list_style' => 'radios',
      'list_names' => 'name',
      'use_submit' => TRUE,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form['list_style'] = [
      '#type' => 'radios',
      '#title' => $this->t('Currency list style'),
      '#options' => [
        'radios' => $this->t('Radio buttons'),
        'select' => $this->t('List'),
      ],
      '#default_value' => $this->configuration['list_style'],
    ];
    $form['list_names'] = [
      '#type' => 'radios',
      '#title' => $this->t('Currency names'),
      '#options' => [
        'name' => $this->t('Name'),
        'code' => $this->t('Code'),
        'both' => $this->t('Both'),
      ],
      '#default_value' => $this->configuration['list_names'],
    ];
    $form['use_submit'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Use <em>Select</em> button'),
      '#description' => $this->t('Without button, the currency will be changed immediately on selection'),
      '#default_value' => $this->configuration['use_submit'],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    $this->configuration['list_style'] = $values['list_style'];
    $this->configuration['list_names'] = $values['list_names'];
    $this->configuration['use_submit'] = $values['use_submit'];
  }

  /**
   * {@inheritdoc}
   * 
   * A form is needed for two reasons:
   * - the submit action;
   * - radios can only be rendered inside a form, not directly in the render array of a block.
   */
  public function build() {
    return $this->formBuilder->getForm(CurrencySelectorForm::class, $this->configuration);
  }

  /**
   * The selector block is suppressed during checkout — changing the currency that late in the
   * ordering process is not something that can be handled reliably. Users should go back to the cart
   * and start the process again instead.
   */
  public function access(AccountInterface $account, $return_as_object = FALSE) {
    $route_name = $this->currentRouteMatch->getRouteName();
    if (str_starts_with($route_name, 'commerce_checkout.')) {
      return $return_as_object ? AccessResult::forbidden() : FALSE;
    } else {
      return $return_as_object ? AccessResult::allowed() : TRUE;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheMaxAge() {
    return 0;
  }
}
