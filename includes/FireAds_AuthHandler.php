<?php

defined('ABSPATH') or die('No access!');

class FireAds_AuthHandler
{
    private $authActions;

    public function __construct(FireAds_AuthActioner $authActions)
    {
        $this->authActions = $authActions;
    }

    public function attachAuthHooks()
    {
        add_action('wp_login', [$this, 'login'], 10, 2);
        add_action('user_register', [$this, 'register']);
    }

    public function login($userLogin, $user)
    {
        $this->authActions->ifKeyCookieIsValidUpdateFireAdsKeyMetaForUserId($user->ID);
    }

    public function register($userId)
    {
        $this->authActions->ifKeyCookieIsValidUpdateFireAdsKeyMetaForUserId($userId);
        $this->authActions->ifClientIdCookieIsValidAndRowIsInDbUpdatedHasRegistered();
    }
}
