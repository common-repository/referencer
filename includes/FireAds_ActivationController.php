<?php

defined('ABSPATH') or die('No access!');

class FireAds_ActivationController
{
    public function activationListener()
    {
        global $wpdb;

        $wpdb->query("
            CREATE TABLE {$wpdb->prefix}fireads_activities (
                id 
                    BIGINT 
                    UNSIGNED 
                    AUTO_INCREMENT
                    PRIMARY KEY,
                date_add
                    DATETIME,
                total_entries 
                    INT,
                has_registered 
                    BOOLEAN 
            )
        ");
    }
}
