<?php

defined('ABSPATH') or die ('No access!');

class FireAds_AuthActioner
{
    private $globalArrayConditioner;
    private $activityDbManager;

    public function __construct(FireAds_GlobalArrayConditioner $globalArrayConditioner, FireAds_ActivityDbManager $activityDbManager)
    {
        $this->globalArrayConditioner = $globalArrayConditioner;
        $this->activityDbManager = $activityDbManager;
    }

    public function ifKeyCookieIsValidUpdateFireAdsKeyMetaForUserId($userId)
    {
        if ($this->globalArrayConditioner->isFireAdsKeyCookieSetAndValid()) {
            $this->updateFireAdsKeyMetaForUserId($userId);
        }
    }

    public function updateFireAdsKeyMetaForUserId($userId)
    {
        update_user_meta($userId, 'fireadsClient', [
            'key' => sanitize_text_field($_COOKIE['fireAdsKey']),
            'time' => date("Y-m-d H:i:s")
        ]);
    }

    public function ifClientIdCookieIsValidAndRowIsInDbUpdatedHasRegistered()
    {
        if ($this->globalArrayConditioner->isFireAdsClientIdCookieSetAndValid()
            && $this->activityDbManager->doesActivityRecordWithIdExists(sanitize_text_field($_COOKIE['fireAdsClientId']))) {
            $this->activityDbManager->updateHasRegisteredBooleanToTrue();
        }
    }
}
