<?php

namespace Drupal\commerce_currencies\Form;

use Drupal\commerce_currencies\Ajax\PageReloadCommand;
use Drupal\commerce_currencies\CurrentCurrency;
use Drupal\commerce_price\Entity\Currency;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Entity\{EntityStorageInterface, EntityTypeManagerInterface};
use Drupal\Core\Form\{FormBase, FormStateInterface};
use Symfony\Component\DependencyInjection\ContainerInterface;

class CurrencySelectorForm extends FormBase {
  protected EntityStorageInterface $storage;
  protected CurrentCurrency $currentCurrency;

  public function __construct(EntityTypeManagerInterface $type_manager, CurrentCurrency $current_currency) {
    $this->storage = $type_manager->getStorage('commerce_currency');
    $this->currentCurrency = $current_currency;
  }

  public static function create(ContainerInterface $container): CurrencySelectorForm {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('commerce_currencies.current_currency')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'commerce_currencies_currency_selector_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $configuration = $form_state->getBuildInfo()['args'][0];

    $ids = $this->storage->getQuery()
      ->accessCheck(TRUE)
      ->condition('status', TRUE)
      ->sort('name', 'ASC')
      ->execute();
    $all = $this->storage->loadMultiple($ids);

    $currencies = [];
    foreach ($all as $currency) {
      /** @var Currency $currency */
      switch ($configuration['list_names'] ?? 'name') {
        case 'name':
          $currencies[$currency->getCurrencyCode()] = $currency->getName();
          break;
        case 'code':
          $currencies[$currency->getCurrencyCode()] = $currency->getCurrencyCode();
          break;
        case 'both':
          $currencies[$currency->getCurrencyCode()] = $currency->getName() . ' (' . $currency->getCurrencyCode() . ')';
          break;
      }
    }

    $form['currency'] = [
      '#type' => $configuration['list_style'] ?? 'radios',
      '#options' => $currencies,
      '#default_value' => $this->currentCurrency->getCurrency(),
    ];

    if ($configuration['use_submit']) {
      $form['submit'] = [
        '#type' => 'submit',
        '#value' => $this->t('Select'),
      ];
    } else {
      $form['currency']['#ajax'] = [
        'event' => 'change',
        'callback' => [$this, 'autoSubmitForm'],
      ];
    }

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $selected_currency = $form_state->getValue('currency');
    setrawcookie('commerce_currencies_currency_code', rawurlencode($selected_currency), [
      'expires' => 0,
      'path' => '/',
      'samesite' => 'Strict',
    ]);
  }

  public function autoSubmitForm(array &$form, FormStateInterface $form_state): AjaxResponse {
    $this->submitForm($form, $form_state);

    $ajax_response = new AjaxResponse();
    $ajax_response->addCommand(new PageReloadCommand());
    return $ajax_response;
  }
}
