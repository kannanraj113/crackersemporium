<?php

namespace Drupal\commerce_currencies;

use Drupal\commerce_price\Entity\Currency;
use Drupal\commerce_store\CurrentStoreInterface;
use Drupal\Core\Entity\{EntityStorageInterface, EntityTypeManagerInterface};
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\user\Entity\User;
use Symfony\Component\HttpFoundation\RequestStack;

class CurrentCurrency {
  use StringTranslationTrait;

  protected AccountInterface $currentUser;
  protected RequestStack $requestStack;
  protected CurrentStoreInterface $currentStore;
  protected EntityStorageInterface $currencyStorage;
  protected EntityStorageInterface $userStorage;
  protected \SplObjectStorage $currency;

  public function __construct(AccountInterface $current_user, RequestStack $request_stack, CurrentStoreInterface $current_store, EntityTypeManagerInterface $type_manager) {
    $this->currentUser = $current_user;
    $this->requestStack = $request_stack;
    $this->currentStore = $current_store;
    $this->currencyStorage = $type_manager->getStorage('commerce_currency');
    $this->userStorage = $type_manager->getStorage('user');
    $this->currency = new \SplObjectStorage();
  }

  public function getCurrency() {
    $request = $this->requestStack->getCurrentRequest();

    if ($this->currency->contains($request)) {
      return $this->currency[$request];
    } else {
      $currency_code = '';

      // Both the language selector block and the user profile setting use a cookie for quick response
      if (!$request->cookies->has('commerce_currencies_currency_code')) {
        // No cookie yet, try to get from the user's preferences
        /** @var User $user */
        $user = $this->userStorage->load($this->currentUser->id());
        foreach ($user->getFieldDefinitions() as $field) {
          if ($field->getType() == 'commerce_currencies_preferred') {
            $currency_code = $user->get($field->getName())->value;
            setrawcookie('commerce_currencies_currency_code', rawurlencode($currency_code), [
              'expires' => 0,
              'path' => '/',
              'samesite' => 'Strict',
            ]);
            break;
          }
        }
      }

      // No user preference, check cookie from the language selector block
      if (empty($currency_code) && $request->cookies->has('commerce_currencies_currency_code')) {
        $currencies = $this->currencyStorage->loadByProperties([
          'status' => TRUE,
          'currencyCode' => $request->cookies->get('commerce_currencies_currency_code'),
        ]);
        /** @var Currency $currency */
        $currency = reset($currencies);
        if ($currency) {
          $currency_code = $currency->getCurrencyCode();
        }
      }

      if (empty($currency_code)) {
        // Get the store default, if nothing else
        $currency_code = $this->currentStore->getStore()?->getDefaultCurrencyCode() ?? '';
      }

      return $this->currency[$request] = $currency_code;
    }
  }
}
