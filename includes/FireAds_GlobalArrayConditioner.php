<?php

defined('ABSPATH') or die('No access!');

class FireAds_GlobalArrayConditioner
{
    public function isFireAdsKeyGetParamSetAndValid()
    {
        return $this->isKeyFromGlobalArrayAlphanumeric('fireads-key', $_GET);
    }

    public function isFireAdsKeyCookieSetAndValid()
    {
        return $this->isKeyFromGlobalArrayAlphanumeric('fireAdsKey', $_COOKIE);
    }

    public function isFireAdsClientIdCookieSetAndValid()
    {
        return isset($_COOKIE['fireAdsClientId']) && is_numeric($_COOKIE['fireAdsClientId']);
    }

    private function isKeyFromGlobalArrayAlphanumeric($key, &$globalArray)
    {
        return isset($globalArray[$key]) && preg_match('/^\w+$/', $globalArray[$key]);
    }
}
