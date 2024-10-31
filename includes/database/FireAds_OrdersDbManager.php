<?php

defined('ABSPATH') or die('No access!');

class FireAds_OrdersDbManager
{
    public function getFireAdsOrdersCount()
    {
        global $wpdb;

        return $wpdb->get_var("
            SELECT 
                count(post_id)
            FROM 
                {$wpdb->prefix}postmeta
            WHERE 
                meta_key = 'fireads_key'
        ");
    }

    public function getFireAdsOrdersIds()
    {
        global $wpdb;

        $ordersIdsResult = $wpdb->get_results("
            SELECT 
                post_id
            FROM
                {$wpdb->prefix}postmeta
            WHERE
                meta_key = 'fireads_key'
            ORDER BY
                post_id DESC
        ");
        $ordersIds = [];
        foreach ($ordersIdsResult as $orderIdResult) {
            array_push($ordersIds, $orderIdResult->post_id);
        }
        return $ordersIds;
    }
}
