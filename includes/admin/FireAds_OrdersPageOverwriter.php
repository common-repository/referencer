<?php

defined('ABSPATH') or die('No access!');

class FireAds_OrdersPageOverwriter
{
    public function addFireAdsColumn($currentColumns)
    {
        $newColumns = [];
        foreach ($currentColumns as $columnName => $columnText) {
            $newColumns[$columnName] = $columnText;

            if ('order_total' === $columnName) {
                $newColumns['fireAds'] = '<div align="center">FireAds</div>';
            }
        }
        return $newColumns;
    }

    public function fillFireAdsColumn($column)
    {
        if ($column === 'fireAds') {
            global $the_order;
            if ($the_order->get_meta('fireads_key')) {
                echo '<div align="center">Yes</div>';
            } else {
                echo '<div align="center">No</div>';
            }
        }
    }
}
