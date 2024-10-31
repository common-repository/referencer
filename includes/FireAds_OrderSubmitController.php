<?php

defined('ABSPATH') or die('No access!');

class FireAds_OrderSubmitController
{
    private $referenceKeyManager;
    private $globalArrayConditioner;

    private $order;
    private $fireAdsKey;

    public function __construct(FireAds_ReferenceKeyManager $referenceKeyManager, FireAds_GlobalArrayConditioner $globalArrayConditioner)
    {
        $this->referenceKeyManager = $referenceKeyManager;
        $this->globalArrayConditioner = $globalArrayConditioner;
    }

    public function attachOrderSubmitHook()
    {
        add_action('woocommerce_checkout_order_processed', [$this, 'handleOrderSubmit'], 1);
    }

    public function handleOrderSubmit($orderId)
    {
        $this->order = wc_get_order($orderId);

        $this->fireAdsKey = $this->referenceKeyManager->getReferenceKeyFromUserByOrder($this->order);
        if ($this->fireAdsKey) {
            $this->updateFireAdsOrderMetaData();
            return;
        }

        if ($this->globalArrayConditioner->isFireAdsKeyCookieSetAndValid() && !is_admin()) {
            $this->fireAdsKey = sanitize_text_field($_COOKIE['fireAdsKey']);
            $this->updateFireAdsOrderMetaData();
        }
    }

    private function updateFireAdsOrderMetaData()
    {
        $this->order->update_meta_data('fireads_key', $this->fireAdsKey);
        $this->order->save();
    }
}
