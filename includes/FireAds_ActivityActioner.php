<?php

defined('ABSPATH') or die('No access!');

class FireAds_ActivityActioner
{
    private $activityDbManager;
    private $cookieManager;

    public function __construct(FireAds_ActivityDbManager $activityDbManager, FireAds_CookieManager $cookieManager)
    {
        $this->activityDbManager = $activityDbManager;
        $this->cookieManager = $cookieManager;
    }

    public function runNewFireAdsClientActions()
    {
        $this->cookieManager->setCookie(
            'fireAdsClientId',
            $this->activityDbManager->storeNewActivityToDbAndGetId()
        );
    }

    public function runOldFireAdsClientActions()
    {
        ($this->activityDbManager->doesActivityRecordWithIdExists(sanitize_text_field($_COOKIE['fireAdsClientId']))) ?
            $this->activityDbManager->incrementTotalEntries() :
            $this->cookieManager->deleteCookie('fireAdsClientId');
    }
}
