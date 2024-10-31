<?php

defined('ABSPATH') or die('No access!');

class FireAds_RedirectActioner
{
    private $authActions;
    private $activityActions;
    private $cookieManager;

    public function __construct(
        FireAds_CookieManager $cookieManager,
        FireAds_AuthActioner $authActions,
        FireAds_ActivityActioner $activityActions
    )
    {
        $this->cookieManager = $cookieManager;
        $this->authActions = $authActions;
        $this->activityActions = $activityActions;
    }

    public function runActions()
    {
        $this->cookieManager->setCookie('fireAdsKey', sanitize_text_field($_GET['fireads-key']));

        if (is_user_logged_in()) {
            $this->authActions->updateFireAdsKeyMetaForUserId(get_current_user_id());
        }

        if (!isset($_COOKIE['fireAdsClientId'])) {
            $this->activityActions->runNewFireAdsClientActions();
        }

        if (!empty($_GET['deep_link'])) {
            if (wp_redirect(sanitize_text_field($_GET['deep_link']))) {
                exit;
            }
        }
    }
}
