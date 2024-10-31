<?php

defined('ABSPATH') or die('No Access!');

class FireAds_OrdersPageDataBuilder
{
    private $ordersDbManager;

    public function __construct(FireAds_OrdersDbManager $ordersDbManager)
    {
        $this->ordersDbManager = $ordersDbManager;
    }

    /**
     * It returns array with fire ads orders data grouped by months
     * Example return value:
     * [
     *     '2019 June' => [
     *          'count' => 4,
     *          'currencySpecificData' => [
     *                'GBP' => [
     *                    'count' => 2,
     *                    'totalValue' => 14,
     *                    'totalValueWithoutShipping' => 10
     *                ],
     *                'PLN' => [
     *                    'count' => 2,
     *                    'totalValue' => 16.5,
     *                    'totalValueWithoutShipping' => 12.5
     *                ]
     *           ]
     *       ],
     *       '2019 May' => [
     *           'count' => 3,
     *           'currencySpecificData' => [
     *                'GBP' => [
     *                     'count' => 2,
     *                     'totalValue' => 30,
     *                     'totalValueWithoutShipping' => 25
     *                 ],
     *                 'PLN' => [
     *                      'count' => 1,
     *                      'totalValue' => 40.50,
     *                      'totalValueWithoutShipping' => 37.50
     *                 ]
     *            ]
     *      ]
     * ]
     */
    public function getMonthlyGroupedOrders()
    {
        $monthlyGroupedOrders = [];
        $ordersIds = $this->ordersDbManager->getFireAdsOrdersIds();

        foreach ($ordersIds as $orderId) {
            $order = wc_get_order($orderId);
            $date = date('Y F', strtotime($order->get_date_created()));

            if (!isset($monthlyGroupedOrders[$date])) {
                $monthlyGroupedOrders[$date] = [
                    'count' => 0,
                    'currencySpecificData' => []
                ];
            }

            $monthlyGroupedOrders[$date]['count']++;

            $currency = $order->get_currency();

            if (!isset($monthlyGroupedOrders[$date]['currencySpecificData'][$currency])) {
                $monthlyGroupedOrders[$date]['currencySpecificData'][$currency] = [
                    'count' => 0,
                    'totalValue' => 0,
                    'totalValueWithoutShipping' => 0
                ];
            }

            $monthlyGroupedOrders[$date]['currencySpecificData'][$currency]['count']++;

            $orderTotalValue = (double)$order->get_total();
            $orderShipping = (double)$order->get_shipping_total();
            $monthlyGroupedOrders[$date]['currencySpecificData'][$currency]['totalValue'] += $orderTotalValue;
            $monthlyGroupedOrders[$date]['currencySpecificData'][$currency]['totalValueWithoutShipping'] += $orderTotalValue - $orderShipping;
        }

        return $monthlyGroupedOrders;
    }
}
