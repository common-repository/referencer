<?php

defined('ABSPATH') or die('No access!');

class FireAds_CookieManager
{
    public function setCookie($name, $value)
    {
        $_COOKIE[$name] = $value;
        setcookie($name, $value, time() + 60 * 60 * 24 * 365 * 10, '/');
    }

    public function deleteCookie($name)
    {
        setcookie($name, '', time() - 3600, '/');
    }
}
