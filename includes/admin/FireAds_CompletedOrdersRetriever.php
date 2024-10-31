<?php

class FireAds_CompletedOrdersRetriever
{
    /**
     * @param $dateFrom Y-m-d
     * @param $dateTo Y-m-d
     * @return array array of completed fireads orders from date range in desc order
     */
    public function getCompletedOrdersFromDateRange($status, $dateFrom, $dateTo, $currentPage)
    {
        $conditions = [
            'limit' => '20',
            'paged' => $currentPage,
            'orderby' => 'ID',
            'order' => 'DESC',
            'meta_key' => 'fireads_key',
            'meta_compare' => 'EXISTS'
        ];

        if ($status) {
            $conditions['status'] = $status;
        }

        if ($dateFrom && $dateTo) {
            $conditions['date_created'] = "$dateFrom...$dateTo";
        } else if ($dateFrom) {
            $conditions['date_created'] = ">=$dateFrom";
        } else if ($dateTo) {
            $conditions['date_created'] = "<=$dateTo";
        }

        return wc_get_orders($conditions);
    }
}
