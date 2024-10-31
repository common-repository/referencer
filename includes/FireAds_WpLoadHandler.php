<?php

defined('ABSPATH') or die('No access!');

class FireAds_WpLoadHandler
{
    private $globalArrayConditioner;
    private $fireAdsRedirectActions;
    private $activityActioner;

    public function __construct(
        FireAds_GlobalArrayConditioner $globalArrayConditioner,
        FireAds_RedirectActioner $fireAdsRedirectActions,
        FireAds_ActivityActioner $activityActioner
    )
    {
        $this->globalArrayConditioner = $globalArrayConditioner;
        $this->fireAdsRedirectActions = $fireAdsRedirectActions;
        $this->activityActioner = $activityActioner;
    }

    public function attachLoadHook()
    {
        add_action('wp_loaded', [$this, 'handleLoad']);
    }

    public function handleLoad()
    {
//        for ($i = 0; $i < 500; $i++) {
//            $order = wc_create_order();
//            $order->set_date_created('2020-01-19');
//            $order->set_total($i);
//            $order->set_status('completed');
//            $order->update_meta_data('fireads_key', 'key');
//            $order->save();
//        }

        if ($this->globalArrayConditioner->isFireAdsKeyGetParamSetAndValid()) {
            $this->fireAdsRedirectActions->runActions();
        } else if ($this->globalArrayConditioner->isFireAdsClientIdCookieSetAndValid()) {
            $this->activityActioner->runOldFireAdsClientActions();
        }
    }
}
