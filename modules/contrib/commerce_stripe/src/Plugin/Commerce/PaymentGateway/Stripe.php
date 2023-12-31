<?php

namespace Drupal\commerce_stripe\Plugin\Commerce\PaymentGateway;

use Drupal\commerce_order\Entity\OrderInterface;
use Drupal\commerce_payment\Exception\InvalidRequestException;
use Drupal\commerce_payment\Exception\SoftDeclineException;
use Drupal\commerce_price\MinorUnitsConverterInterface;
use Drupal\commerce_stripe\ErrorHelper;
use Drupal\commerce_payment\CreditCard;
use Drupal\commerce_payment\Entity\PaymentInterface;
use Drupal\commerce_payment\Entity\PaymentMethodInterface;
use Drupal\commerce_payment\Exception\HardDeclineException;
use Drupal\commerce_payment\Exception\PaymentGatewayException;
use Drupal\commerce_payment\PaymentMethodTypeManager;
use Drupal\commerce_payment\PaymentTypeManager;
use Drupal\commerce_payment\Plugin\Commerce\PaymentGateway\OnsitePaymentGatewayBase;
use Drupal\commerce_price\Price;
use Drupal\commerce_stripe\Event\PaymentIntentEvent;
use Drupal\commerce_stripe\Event\TransactionDataEvent;
use Drupal\commerce_stripe\Event\PaymentMethodCreateEvent;
use Drupal\commerce_stripe\Event\StripeEvents;
use Drupal\Component\Datetime\TimeInterface;
use Drupal\Component\Utility\NestedArray;
use Drupal\Component\Uuid\UuidInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Extension\ModuleExtensionList;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Site\Settings;
use Stripe\Balance;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;
use Stripe\ApiRequestor;
use Stripe\HttpClient\CurlClient;
use Stripe\PaymentMethod;
use Stripe\Refund;
use Stripe\Stripe as StripeLibrary;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Provides the Stripe payment gateway.
 *
 * @CommercePaymentGateway(
 *   id = "stripe",
 *   label = "Stripe Card Element",
 *   display_label = "Stripe Card Element",
 *   forms = {
 *     "add-payment-method" = "Drupal\commerce_stripe\PluginForm\Stripe\PaymentMethodAddForm",
 *   },
 *   payment_method_types = {"credit_card"},
 *   credit_card_types = {
 *     "amex", "dinersclub", "discover", "jcb", "maestro", "mastercard", "visa", "unionpay"
 *   },
 *   js_library = "commerce_stripe/form",
 *   requires_billing_information = FALSE,
 * )
 */
class Stripe extends OnsitePaymentGatewayBase implements StripeInterface {

  /**
   * The event dispatcher.
   *
   * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
   */
  protected $eventDispatcher;

  /**
   * The module extension list.
   *
   * @var \Drupal\Core\Extension\ModuleExtensionList
   */
  protected $moduleExtensionList;

  /**
   * The UUID service.
   *
   * @var \Drupal\Component\Uuid\UuidInterface
   */
  protected $uuidService;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
      $container->get('plugin.manager.commerce_payment_type'),
      $container->get('plugin.manager.commerce_payment_method_type'),
      $container->get('datetime.time'),
      $container->get('commerce_price.minor_units_converter'),
      $container->get('event_dispatcher'),
      $container->get('extension.list.module'),
      $container->get('uuid')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager, PaymentTypeManager $payment_type_manager, PaymentMethodTypeManager $payment_method_type_manager, TimeInterface $time, MinorUnitsConverterInterface $minor_units_converter, EventDispatcherInterface $event_dispatcher, ModuleExtensionList $module_extension_list, UuidInterface $uuid = NULL) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $entity_type_manager, $payment_type_manager, $payment_method_type_manager, $time, $minor_units_converter);

    if (!$uuid) {
      @trigger_error('Calling ' . __METHOD__ . '() without the $uuid argument is deprecated in commerce_stripe:8.x-1.0-rc7 and is removed from commerce_stripe:1.0.');
      $uuid = \Drupal::service('uuid');
    }
    $this->eventDispatcher = $event_dispatcher;
    $this->moduleExtensionList = $module_extension_list;
    $this->uuidService = $uuid;
    $this->init();
  }

  /**
   * Re-initializes the SDK after the plugin is unserialized.
   */
  public function __wakeup() {
    parent::__wakeup();

    $this->init();
  }

  /**
   * Initializes the SDK.
   */
  protected function init() {
    $extension_info = $this->moduleExtensionList->getExtensionInfo('commerce_stripe');
    $version = !empty($extension_info['version']) ? $extension_info['version'] : '8.x-1.0-dev';
    StripeLibrary::setAppInfo('Centarro Commerce for Drupal', $version, 'https://www.drupal.org/project/commerce_stripe', 'pp_partner_Fa3jTqCJqTDtHD');

    // If Drupal is configured to use a proxy for outgoing requests, make sure
    // that the proxy CURLOPT_PROXY setting is passed to the Stripe SDK client.
    $http_client_config = Settings::get('http_client_config');
    if (!empty($http_client_config['proxy']['https'])) {
      $curl = new CurlClient([CURLOPT_PROXY => $http_client_config['proxy']['https']]);
      ApiRequestor::setHttpClient($curl);
    }

    StripeLibrary::setApiKey($this->configuration['secret_key']);
    StripeLibrary::setApiVersion('2019-12-03');
  }

  /**
   * {@inheritdoc}
   */
  public function getPublishableKey() {
    return $this->configuration['publishable_key'];
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'publishable_key' => '',
      'secret_key' => '',
      'enable_credit_card_icons' => TRUE,
    ] + parent::defaultConfiguration();
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildConfigurationForm($form, $form_state);

    $form['publishable_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Publishable Key'),
      '#default_value' => $this->configuration['publishable_key'],
      '#required' => TRUE,
    ];

    $form['secret_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Secret Key'),
      '#default_value' => $this->configuration['secret_key'],
      '#required' => TRUE,
    ];

    $form['validate_api_keys'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Validate API keys upon form submission.'),
      '#default_value' => TRUE,
    ];

    $form['enable_credit_card_icons'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enable Credit Card Icons'),
      '#description' => $this->t('Enabling this setting will display credit card icons in the payment section during checkout.'),
      '#default_value' => $this->configuration['enable_credit_card_icons'],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateConfigurationForm(array &$form, FormStateInterface $form_state) {
    parent::validateConfigurationForm($form, $form_state);

    if (!$form_state->getErrors()) {
      $values = $form_state->getValue($form['#parents']);
      // Validate the secret key.
      $expected_livemode = $values['mode'] == 'live';
      if (!empty($values['secret_key']) && $values['validate_api_keys']) {
        try {
          StripeLibrary::setApiKey($values['secret_key']);
          // Make sure we use the right mode for the secret keys.
          if (Balance::retrieve()->offsetGet('livemode') !== $expected_livemode) {
            $form_state->setError($form['secret_key'], $this->t('The provided secret key is not for the selected mode (@mode).', ['@mode' => $values['mode']]));
          }
        }
        catch (ApiErrorException $e) {
          $form_state->setError($form['secret_key'], $this->t('Invalid secret key.'));
        }
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    parent::submitConfigurationForm($form, $form_state);

    if (!$form_state->getErrors()) {
      $values = $form_state->getValue($form['#parents']);
      $this->configuration['publishable_key'] = $values['publishable_key'];
      $this->configuration['secret_key'] = $values['secret_key'];
      $this->configuration['enable_credit_card_icons'] = $values['enable_credit_card_icons'];
    }
  }

  /**
   * {@inheritdoc}
   */
  public function createPayment(PaymentInterface $payment, $capture = TRUE) {
    $this->assertPaymentState($payment, ['new']);
    $payment_method = $payment->getPaymentMethod();
    assert($payment_method instanceof PaymentMethodInterface);
    $this->assertPaymentMethod($payment_method);
    $order = $payment->getOrder();
    assert($order instanceof OrderInterface);
    $intent_id = $order->getData('stripe_intent');
    try {
      if (!empty($intent_id)) {
        $intent = PaymentIntent::retrieve($intent_id);
      }
      else {
        // If there is no payment intent, it means we are not in a checkout
        // flow with the stripe review pane, so we should assume the
        // customer is not available for SCA and create an immediate
        // off_session payment intent.
        $intent_attributes = [
          'confirm'        => TRUE,
          'off_session'    => TRUE,
          'capture_method' => $capture ? 'automatic' : 'manual',
        ];
        $intent = $this->createPaymentIntent($order, $intent_attributes, $payment);
      }
      if ($intent->status === PaymentIntent::STATUS_REQUIRES_CONFIRMATION) {
        $intent = $intent->confirm();
      }
      if ($intent->status === PaymentIntent::STATUS_REQUIRES_ACTION) {
        throw new SoftDeclineException('The payment intent requires action by the customer for authentication');
      }
      if (!in_array($intent->status, [PaymentIntent::STATUS_REQUIRES_CAPTURE, PaymentIntent::STATUS_SUCCEEDED], TRUE)) {
        $order->set('payment_method', NULL);
        $this->deletePaymentMethod($payment_method);
        if ($intent->status === PaymentIntent::STATUS_CANCELED) {
          $order->setData('stripe_intent', NULL);
        }

        if (is_object($intent->last_payment_error)) {
          $error = $intent->last_payment_error;
          $decline_message = sprintf('%s: %s', $error->type, $error->message ?? '');
        }
        else {
          $decline_message = $intent->last_payment_error;
        }
        throw new HardDeclineException($decline_message);
      }
      if (count($intent->charges->data) === 0) {
        throw new HardDeclineException(sprintf('The payment intent %s did not have a charge object.', $intent->id));
      }
      $next_state = $capture ? 'completed' : 'authorization';
      $payment->setState($next_state);
      $payment->setRemoteId($intent->id);
      $payment->save();

      // Add metadata and extra transaction data where required.
      $event = new TransactionDataEvent($payment);
      $this->eventDispatcher->dispatch($event, StripeEvents::TRANSACTION_DATA);

      // Update the transaction data from additional information added through
      // the event.
      $metadata = $intent->metadata->toArray();
      $metadata += $event->getMetadata();

      PaymentIntent::update($intent->id, [
        'metadata' => $metadata,
      ]);

      $order->unsetData('stripe_intent');
      $order->save();
    }
    catch (ApiErrorException $e) {
      ErrorHelper::handleException($e);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function capturePayment(PaymentInterface $payment, Price $amount = NULL) {
    $this->assertPaymentState($payment, ['authorization']);
    // If not specified, capture the entire amount.
    $amount = $amount ?: $payment->getAmount();

    try {
      $remote_id = $payment->getRemoteId();
      $intent = NULL;
      if (strpos($remote_id, "pi_") === 0) {
        $intent = PaymentIntent::retrieve($remote_id);
        $intent_id = $intent->id;
        $charge = Charge::retrieve($intent['charges']['data'][0]->id);
      }
      else {
        $charge = Charge::retrieve($remote_id);
        $intent_id = $charge->payment_intent;
      }

      $amount_to_capture = $this->minorUnitsConverter->toMinorUnits($amount);
      if (!empty($intent_id)) {
        if (empty($intent)) {
          $intent = PaymentIntent::retrieve($intent_id);
        }
        if ($intent->status == 'requires_capture') {
          $intent->capture(['amount_to_capture' => $amount_to_capture]);
        }
        if ($intent->status == 'succeeded') {
          $payment->setState('completed');
          $payment->save();
        }
        else {
          throw new PaymentGatewayException('Only requires_capture PaymentIntents can be captured.');
        }
      }
      else {
        $charge->amount = $amount_to_capture;
        $transaction_data = [
          'amount' => $charge->amount,
        ];
        $charge->capture($transaction_data);
      }
    }
    catch (ApiErrorException $e) {
      ErrorHelper::handleException($e);
    }

    $payment->setState('completed');
    $payment->setAmount($amount);
    $payment->save();
  }

  /**
   * {@inheritdoc}
   */
  public function voidPayment(PaymentInterface $payment) {
    $this->assertPaymentState($payment, ['authorization']);
    // Void Stripe payment - release uncaptured payment.
    try {
      $remote_id = $payment->getRemoteId();
      $intent = NULL;
      if (strpos($remote_id, "pi_") === 0) {
        $intent = PaymentIntent::retrieve($remote_id);
        $intent_id = $intent->id;
      }
      else {
        $charge = Charge::retrieve($remote_id);
        $intent_id = $charge->payment_intent;
      }

      if (!empty($intent_id)) {
        if (empty($intent)) {
          $intent = PaymentIntent::retrieve($intent_id);
        }
        $statuses_to_void = [
          'requires_payment_method',
          'requires_capture',
          'requires_confirmation',
          'requires_action',
        ];
        if (!in_array($intent->status, $statuses_to_void)) {
          throw new PaymentGatewayException('The PaymentIntent cannot be voided.');
        }
        $intent->cancel();
        $data['payment_intent'] = $intent->id;
      }
      else {
        $data = [
          'charge' => $remote_id,
        ];
        // Voiding an authorized payment is done by creating a refund.
        $release_refund = Refund::create($data);
        ErrorHelper::handleErrors($release_refund);
      }
    }
    catch (ApiErrorException $e) {
      ErrorHelper::handleException($e);
    }

    $payment->setState('authorization_voided');
    $payment->save();
  }

  /**
   * {@inheritdoc}
   */
  public function refundPayment(PaymentInterface $payment, Price $amount = NULL) {
    $this->assertPaymentState($payment, ['completed', 'partially_refunded']);
    // If not specified, refund the entire amount.
    $amount = $amount ?: $payment->getAmount();
    $this->assertRefundAmount($payment, $amount);

    try {
      $remote_id = $payment->getRemoteId();
      $minor_units_amount = $this->minorUnitsConverter->toMinorUnits($amount);
      $data = ['amount' => $minor_units_amount];

      if (strpos($remote_id, "pi_") === 0) {
        $data['payment_intent'] = $remote_id;
      }
      else {
        $data['charge'] = $remote_id;
      }

      $refund = Refund::create($data, [
        'idempotency_key' => $this->uuidService->generate(),
      ]);
      ErrorHelper::handleErrors($refund);
    }
    catch (ApiErrorException $e) {
      ErrorHelper::handleException($e);
    }

    $old_refunded_amount = $payment->getRefundedAmount();
    $new_refunded_amount = $old_refunded_amount->add($amount);
    if ($new_refunded_amount->lessThan($payment->getAmount())) {
      $payment->setState('partially_refunded');
    }
    else {
      $payment->setState('refunded');
    }

    $payment->setRefundedAmount($new_refunded_amount);
    $payment->save();
  }

  /**
   * {@inheritdoc}
   */
  public function createPaymentMethod(PaymentMethodInterface $payment_method, array $payment_details) {
    $required_keys = [
      // The expected keys are payment gateway specific and usually match
      // the PaymentMethodAddForm form elements. They are expected to be valid.
      'stripe_payment_method_id',
    ];
    foreach ($required_keys as $required_key) {
      if (empty($payment_details[$required_key])) {
        throw new InvalidRequestException(sprintf('$payment_details must contain the %s key.', $required_key));
      }
    }

    // Allow alteration of the payment method before remote creation.
    $event = new PaymentMethodCreateEvent($payment_method, $payment_details);
    $this->eventDispatcher->dispatch($event, StripeEvents::PAYMENT_METHOD_CREATE);

    $remote_payment_method = $this->doCreatePaymentMethod($payment_method, $payment_details);
    $payment_method->card_type = $this->mapCreditCardType($remote_payment_method['brand']);
    $payment_method->card_number = $remote_payment_method['last4'];
    $payment_method->card_exp_month = $remote_payment_method['exp_month'];
    $payment_method->card_exp_year = $remote_payment_method['exp_year'];
    $expires = CreditCard::calculateExpirationTimestamp($remote_payment_method['exp_month'], $remote_payment_method['exp_year']);
    $payment_method->setRemoteId($payment_details['stripe_payment_method_id']);
    $payment_method->setExpiresTime($expires);
    $payment_method->save();
  }

  /**
   * {@inheritdoc}
   */
  public function deletePaymentMethod(PaymentMethodInterface $payment_method) {
    // Delete the remote record.
    $payment_method_remote_id = $payment_method->getRemoteId();
    try {
      $remote_payment_method = PaymentMethod::retrieve($payment_method_remote_id);
      if ($remote_payment_method->customer) {
        $remote_payment_method->detach();
      }
    }
    catch (ApiErrorException $e) {
      ErrorHelper::handleException($e);
    }
    $payment_method->delete();
  }

  /**
   * {@inheritdoc}
   */
  public function createPaymentIntent(OrderInterface $order, $intent_attributes = [], PaymentInterface $payment = NULL) {
    if (is_bool($intent_attributes)) {
      $intent_attributes = [
        'capture_method' => $intent_attributes ? 'automatic' : 'manual',
      ];
      @trigger_error('Passing a boolean representing capture method as the second parameter to StripeInterface::createPaymentIntent() is deprecated in commerce_stripe:8.x-1.0 and this parameter must be an array of payment intent attributes in commerce_stripe:9.x-2.0. See https://www.drupal.org/project/commerce_stripe/issues/3259211', E_USER_DEPRECATED);
    }

    /** @var \Drupal\commerce_payment\Entity\PaymentMethodInterface $payment_method */
    $payment_method = $payment ? $payment->getPaymentMethod() : $order->get('payment_method')->entity;
    /** @var \Drupal\commerce_price\Price */
    $amount = $payment ? $payment->getAmount() : $order->getTotalPrice();

    $default_intent_attributes = [
      'amount' => $this->minorUnitsConverter->toMinorUnits($amount),
      'currency' => strtolower($amount->getCurrencyCode()),
      'payment_method_types' => ['card'],
      'metadata' => [
        'order_id' => $order->id(),
        'store_id' => $order->getStoreId(),
      ],
      'payment_method' => $payment_method->getRemoteId(),
      'capture_method' => 'automatic',
    ];

    $customer_remote_id = $this->getRemoteCustomerId($order->getCustomer());
    if (!empty($customer_remote_id)) {
      $default_intent_attributes['customer'] = $customer_remote_id;
    }

    $intent_array = NestedArray::mergeDeep($default_intent_attributes, $intent_attributes);

    // Add metadata and extra transaction data where required.
    $event = new PaymentIntentEvent($order, $intent_array);
    $this->eventDispatcher->dispatch($event, StripeEvents::PAYMENT_INTENT_CREATE);

    // Alter or extend the intent array from additional information added
    // through the event.
    $intent_array = $event->getIntentAttributes();

    try {
      $intent = PaymentIntent::create($intent_array);
      $order->setData('stripe_intent', $intent->id)->save();
    }
    catch (ApiErrorException $e) {
      ErrorHelper::handleException($e);
    }
    return $intent;
  }

  /**
   * Creates the payment method on the gateway.
   *
   * @param \Drupal\commerce_payment\Entity\PaymentMethodInterface $payment_method
   *   The payment method.
   * @param array $payment_details
   *   The gateway-specific payment details.
   *
   * @return array
   *   The payment method information returned by the gateway. Notable keys:
   *   - token: The remote ID.
   *   Credit card specific keys:
   *   - card_type: The card type.
   *   - last4: The last 4 digits of the credit card number.
   *   - expiration_month: The expiration month.
   *   - expiration_year: The expiration year.
   */
  protected function doCreatePaymentMethod(PaymentMethodInterface $payment_method, array $payment_details) {
    $stripe_payment_method_id = $payment_details['stripe_payment_method_id'];
    $owner = $payment_method->getOwner();
    $customer_id = NULL;
    if ($owner && $owner->isAuthenticated()) {
      $customer_id = $this->getRemoteCustomerId($owner);
    }
    try {
      $stripe_payment_method = PaymentMethod::retrieve($stripe_payment_method_id);
      if ($customer_id) {
        $stripe_payment_method->attach(['customer' => $customer_id]);
        $email = $owner->getEmail();
      }
      // If the user is authenticated, created a Stripe customer to attach the
      // payment method to.
      elseif ($owner && $owner->isAuthenticated()) {
        $email = $owner->getEmail();
        $customer = Customer::create([
          'email' => $email,
          'description' => $this->t('Customer for :mail', [':mail' => $email]),
          'payment_method' => $stripe_payment_method_id,
        ]);
        $customer_id = $customer->id;
        $this->setRemoteCustomerId($owner, $customer_id);
        $owner->save();
      }
      else {
        $email = NULL;
      }

      if ($customer_id && $email) {
        $payment_method_data = [
          'email' => $email,
        ];
        if ($billing_profile = $payment_method->getBillingProfile()) {
          $billing_address = $billing_profile->get('address')->first()->toArray();
          $payment_method_data['address'] = [
            'city' => $billing_address['locality'] ?? '',
            'country' => $billing_address['country_code'] ?? '',
            'line1' => $billing_address['address_line1'] ?? '',
            'line2' => $billing_address['address_line2'] ?? '',
            'postal_code' => $billing_address['postal_code'] ?? '',
            'state' => $billing_address['administrative_area'] ?? '',
          ];
          $name_parts = [];
          foreach (['given_name', 'family_name'] as $name_key) {
            if (!empty($billing_address[$name_key])) {
              $name_parts[] = $billing_address[$name_key];
            }
          }
          $payment_method_data['name'] = implode(' ', $name_parts);
        }
        PaymentMethod::update($stripe_payment_method_id, ['billing_details' => $payment_method_data]);
      }
    }
    catch (ApiErrorException $e) {
      ErrorHelper::handleException($e);
    }
    return $stripe_payment_method->card;
  }

  /**
   * Maps the Stripe credit card type to a Commerce credit card type.
   *
   * @param string $card_type
   *   The Stripe credit card type.
   *
   * @return string
   *   The Commerce credit card type.
   */
  protected function mapCreditCardType($card_type) {
    $map = [
      'amex' => 'amex',
      'diners' => 'dinersclub',
      'discover' => 'discover',
      'jcb' => 'jcb',
      'mastercard' => 'mastercard',
      'visa' => 'visa',
      'unionpay' => 'unionpay',
    ];
    if (!isset($map[$card_type])) {
      throw new HardDeclineException(sprintf('Unsupported credit card type "%s".', $card_type));
    }

    return $map[$card_type];
  }

}
