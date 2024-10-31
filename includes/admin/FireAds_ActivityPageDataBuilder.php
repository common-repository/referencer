<?php

defined('ABSPATH') or die('No Access!');

class FireAds_ActivityPageDataBuilder
{
    /**
     * It returns array with fire ads activities data grouped by months
     * Example return value:
     * [
     *     'total' => 7
     *     'totalRegistered' => 4,
     *     'monthlyGrouped' => [
     *           '2019 June' => [
     *               'total' => 55,
     *               'totalRegistered'  => 3,
     *            ],
     *            '2019 May' => [{
     *               'total' => 25,
     *               'totalRegistered'  => 1,
     *           ]
     *      ]
     * ]
     */
    public function getData()
    {
        $builtData = [
            'total' => 0,
            'totalRegistered' => 0,
            'totalUnique' => 0,
            'monthlyGrouped' => []
        ];

        global $wpdb;
        $activities = $wpdb->get_results("
            SELECT 
                *
            FROM
                {$wpdb->prefix}fireads_activities
            ORDER BY
                id DESC
        ");

        foreach ($activities as $activity) {
            $builtData['total'] += $activity->total_entries;
            $builtData['totalUnique'] += 1;

            $date = date('Y F', strtotime($activity->date_add));
            if (!isset($builtData['monthlyGrouped'][$date])) {
                $builtData['monthlyGrouped'][$date] = [
                    'total' => 0,
                    'totalRegistered' => 0
                ];
            }

            $builtData['monthlyGrouped'][$date]['total'] += $activity->total_entries;
            $builtData['monthlyGrouped'][$date]['totalUnique'] += 1;

            if ($activity->has_registered) {
                $builtData['totalRegistered'] += 1;
                $builtData['monthlyGrouped'][$date]['totalRegistered'] += 1;
            }
        }

        return $builtData;
    }
}
