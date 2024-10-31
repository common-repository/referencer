<?php

defined('ABSPATH') or die('No access!');

class FireAds_PostbackSender
{
    /** @var WC_Order */
    public $order;
    public $referenceKey;
    private $postData;

    public function sendPostBack()
    {
        $floatPercent = (float)get_option('fireads_procent') / 100;

        $this->postData = [
            'status' => null,
            'orderId' => $this->order->get_id(),
            'currency' => $this->order->get_currency(),
            'key' => $this->referenceKey,
            'value' => $floatPercent * $this->order->get_total()
        ];
        $this->setUpStatus();
        $this->send();
    }

    private function setUpStatus()
    {
        switch ($this->order->get_status()) {
            case 'cancelled':
            case 'refunded':
            case 'failed':
                $this->postData['status'] = -1;
                break;
            case 'pending':
            case 'processing':
            case 'on-hold':
                $this->postData['status'] = 0;
                break;
            case 'completed':
                $this->postData['status'] = 1;
                break;
        }
    }

    private function send()
    {
        $postbackUrl = get_option('fireads_postback_url');

        if (!$postbackUrl) {
            $postbackUrl = get_option('fireadsPostbackUrl');
        }

        if (!$postbackUrl) {
            $postbackUrl = get_option('postback_url');
        }

        if (!$postbackUrl) {
            $postbackUrl = get_option('postbackUrl');
        }

        if (!$postbackUrl) {
            die("
                Postback url not set! <br>
                <a href='" . get_home_url() . "'>Redirect to home page</a>.   
            ");
        }

        $response = wp_remote_post($postbackUrl, [
            'method' => 'POST',
            'body' => $this->postData
        ]);

        if (is_wp_error($response)) {
            die("
                    There was an error while sending postback! Please check provided postback URL! <br>
                    <a href='" . get_home_url() . "'>Redirect to home page</a>.
           ");
        }
    }
}
