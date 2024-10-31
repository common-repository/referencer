<?php

defined('ABSPATH') or die('No access!');

class FireAds_DateRangeRetrievier
{
    public function getFromDate()
    {
        return $this->getTimeFromGETParam('date-from');
    }

    public function getToDate()
    {
        return $this->getTimeFromGETParam('date-to');
    }

    private function getTimeFromGETParam($name)
    {
        return (!isset($_GET[$name]) || !is_numeric($_GET[$name])) ?
            null :
            date('Y-m-d', sanitize_text_field($_GET[$name]));
    }
}
