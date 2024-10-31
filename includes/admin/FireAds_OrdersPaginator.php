<?php

defined('ABSPATH') or die('No access!');

class FireAds_OrdersPaginator
{
    private $searchedOrdersCount;
    private $pagesCount;


    public function getAllOrdersCount($status, $dateFrom, $dateTo)
    {
        global $wpdb;

        $whereQuery = "posts.post_type = 'shop_order'";


        if ($status) {
            $whereQuery .= " AND posts.post_status = 'wc-" . $status . "'";
        }

        if ($dateFrom) {
            $whereQuery .= " AND posts.post_date >= '" . $dateFrom . " 00:00:00'";
        }

        if ($dateTo) {
            print $dateTo;
            $whereQuery .= " AND posts.post_date <= '" . $dateTo . " 23:59:59'";
        }

        $this->searchedOrdersCount = $wpdb->get_var("
            SELECT 
                COUNT(posts.ID) 
            FROM
                {$wpdb->prefix}posts posts
            WHERE 
                $whereQuery
                AND EXISTS (
                    SELECT 
                        meta_key
                    FROM 
                        {$wpdb->prefix}postmeta
                    WHERE 
                        posts.ID = {$wpdb->prefix}postmeta.post_id
                        AND meta_key = 'fireads_key'
                )
        ");

        return $this->searchedOrdersCount;
    }

    public function getPagesCount($ordersCount)
    {
        $this->pagesCount = ceil($ordersCount / 20);
        return $this->pagesCount;
    }

    public function getCurrentPage()
    {
        $currentPage = 1;

        if (isset($_GET['page-number']) && is_numeric($_GET['page-number'])) {
            $currentPage = (int)sanitize_text_field($_GET['page-number']);

            if ($currentPage > $this->pagesCount) {
                $currentPage = $this->pagesCount;
            }
        }

        return $currentPage;
    }
}
