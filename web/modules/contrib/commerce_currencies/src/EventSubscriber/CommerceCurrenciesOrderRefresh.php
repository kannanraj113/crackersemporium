<?php

namespace Drupal\commerce_currencies\EventSubscriber;

use Drupal\commerce_currencies\CurrentCurrency;
use Drupal\commerce_order\Entity\Order;
use Drupal\commerce_order\Event\OrderEvent;
use Drupal\commerce_order\OrderRefreshInterface;
use Drupal\Core\Routing\AdminContext;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Event subscriber for checking for multiple currencies in order.
 */
class CommerceCurrenciesOrderRefresh implements EventSubscriberInterface {
  protected CurrentCurrency $currentCurrency;
  protected AdminContext $adminContext;
  protected AccountInterface $currentUser;
  protected OrderRefreshInterface $orderRefresh;
  protected $handled = [];

  public function __construct(CurrentCurrency $currency, AdminContext $admin_context, AccountInterface $current_user, OrderRefreshInterface $order_refresh) {
    $this->currentCurrency = $currency;
    $this->adminContext = $admin_context;
    $this->currentUser = $current_user;
    $this->orderRefresh = $order_refresh;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [
      'commerce_order.commerce_order.load' => 'check',
    ];
  }

  /**
   * Refresh order if multiple currencies found.
   */
  public function check(OrderEvent $event) {
    /** @var Order $order */
    $order = $event->getOrder();
    if (!isset($this->handled[$order->id()]) && !$this->adminContext->isAdminRoute() && $order->getCustomerId() == $this->currentUser->id() && $order->getState()->value == 'draft' && PHP_SAPI !== 'cli') {
      $total = $order->getTotalPrice();
      $subtotal = $order->getSubtotalPrice();
      $currency = $this->currentCurrency->getCurrency();
      if (($total && $total->getNumber() > 0 && $total->getCurrencyCode() != $currency) || ($subtotal && $subtotal->getNumber() > 0 && $subtotal->getCurrencyCode() != $currency)) {
        $this->handled[$order->id()] = TRUE;
        $this->orderRefresh->refresh($order);
      }
    }
  }
}
