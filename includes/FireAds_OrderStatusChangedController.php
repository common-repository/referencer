<?php

defined('ABSPATH') or die('No access!');

class FireAds_OrderStatusChangedController
{
    private $postBackSender;

    public function __construct(FireAds_PostbackSender $postBackSender)
    {
        $this->postBackSender = $postBackSender;
    }

    public function attachOrderStatusChangedHook()
    {
        add_action('woocommerce_order_status_changed', array($this, 'orderStatusChangeListener'), 2);
    }

    public function orderStatusChangeListener($orderId)
    {
        $order = wc_get_order($orderId);
        $referenceKey = $order->get_meta('fireads_key');
        if ($referenceKey) {
            $this->postBackSender->order = $order;
            $this->postBackSender->referenceKey = $referenceKey;
            $this->postBackSender->sendPostBack();
        }
    }
}
