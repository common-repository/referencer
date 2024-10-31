<?php

defined('ABSPATH') or die('No access!');

class FireAds_ReferenceKeyManager
{
    /**
     * Method that check if user that created order is referenced.
     * If yes return key if not return null.
     * @param $order WC_Order
     * @return null|string;
     */
    public function getReferenceKeyFromUserByOrder($order)
    {
        $referencerMeta = get_user_meta($order->get_user_id(), 'fireadsClient');

        if (!empty($referencerMeta)) {
            return $referencerMeta[0]['key'];
        }
    }
}
