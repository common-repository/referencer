<?php

defined('ABSPATH') or die('No access!');

class FireAds_ActivityDbManager
{
    public function storeNewActivityToDbAndGetId()
    {
        global $wpdb;

        $wpdb->query("
            INSERT INTO {$wpdb->prefix}fireads_activities
                (
                 date_add, 
                 total_entries, 
                 has_registered
                )
            VALUES 
                (
                 '" . date('Y-m-d H:i:s') . "', 
                 '1', 
                 '0'
                )
        ");

        return $wpdb->insert_id;
    }

    public function doesActivityRecordWithIdExists($id)
    {
        global $wpdb;

        return $wpdb->query("
            SELECT 
                1
            FROM
                {$wpdb->prefix}fireads_activities
            WHERE 
                id = $id
        ");
    }

    public function updateHasRegisteredBooleanToTrue()
    {
        $this->updateRowByIdFromCookieSet("
            has_registered = TRUE
        ");
    }

    public function incrementTotalEntries()
    {
        $this->updateRowByIdFromCookieSet("
            total_entries = total_entries + 1
        ");
    }

    private function updateRowByIdFromCookieSet($toSetString)
    {
        global $wpdb;

        $wpdb->query("
            UPDATE
                {$wpdb->prefix}fireads_activities
            SET
                {$toSetString}
            WHERE
                id = " . sanitize_text_field($_COOKIE['fireAdsClientId']) . "
        ");
    }
}
