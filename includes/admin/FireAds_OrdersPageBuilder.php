<?php

defined('ABSPATH') or die('No access!');

class FireAds_OrdersPageBuilder
{
    private $ordersDbManager;
    private $ordersPageDataBuilder;

    public function __construct(FireAds_OrdersPageDataBuilder $ordersPageDataBuilder, FireAds_OrdersDbManager $ordersDbManager)
    {
        $this->ordersDbManager = $ordersDbManager;
        $this->ordersPageDataBuilder = $ordersPageDataBuilder;
    }

    public function createOrdersPage()
    {
        $ordersCount = $this->ordersDbManager->getFireAdsOrdersCount();
        $monthlyGroupedOrders = $this->ordersPageDataBuilder->getMonthlyGroupedOrders();

        ?>
        <style>
            td {
                background: white;
                display: table-cell !important;
            }

            .total-orders-h2 {
                font-weight: 400;
            }
        </style>

        <div class="wrap">
            <h1>FireAds Orders</h1>
            <h2 class="total-orders-h2">All FireAds orders (not completed too): <?php echo $ordersCount ?></h2>
            <table class="wp - list-table widefat fixed striped posts">
                <thead>
                <tr>
                    <th scope="col">Month</th>
                    <th scope="col">Orders</th>
                    <th scope="col">Total value</th>
                    <th scope="col">Without shippings</th>
                </tr>
                </thead>

                <tbody>
                <?php
                foreach ($monthlyGroupedOrders as $month => $monthGroup) {
                    echo '<tr>';
                    echo "<td>$month</td>";
                    echo "<td>" . $monthGroup['count'] . "</td>";
                    echo "<td>";
                    foreach ($monthGroup['currencySpecificData'] as $currency => $currencySpecifData) {
                        echo $currencySpecifData['count']
                            . ' orders - '
                            . $currencySpecifData['totalValue']
                            . ' ' . $currency
                            . '<br>';
                    }
                    echo "</td>";
                    echo "<td>";
                    foreach ($monthGroup['currencySpecificData'] as $currency => $currencySpecifData) {
                        echo $currencySpecifData['count']
                            . ' orders - '
                            . $currencySpecifData['totalValueWithoutShipping']
                            . ' ' . $currency
                            . '<br>';
                    }
                    echo "</td>";
                    echo '</tr>';
                }
                ?>
                </tbody>
            </table>
        </div>
        <?php
    }
}
