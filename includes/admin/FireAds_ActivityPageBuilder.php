<?php

defined('ABSPATH') or die('No access!');

class FireAds_ActivityPageBuilder
{
    private $activityPageDataBuilder;

    public function __construct(FireAds_ActivityPageDataBuilder $activityPageDataBuilder)
    {
        $this->activityPageDataBuilder = $activityPageDataBuilder;
    }

    public function buildPage()
    {
        $data = $this->activityPageDataBuilder->getData();

        ?>
        <style>
            td {
                background: white;
                display: table-cell !important;
            }

            .total-orders-h2 {
                font-weight: 400;
                margin: 8px 0 !important;
            }

            .heighter-headers {
                height: 10px;
            }
        </style>

        <div class="wrap">
            <h1>FireAds Activity</h1>
            <div class="heighter-headers"></div>
            <h2 class="total-orders-h2">Total activity: <b><?php echo $data['total'] ?></b></h2>
            <h2 class="total-orders-h2">Total unique entries: <b><?php echo $data['totalUnique'] ?></b></h2>
            <h2 class="total-orders-h2">Totally registered: <b><?php echo $data['totalRegistered'] ?></b></h2>
            <div class="heighter-headers"></div>
            <table class="wp - list-table widefat fixed striped posts">
                <thead>
                <tr>
                    <th scope="col">Month</th>
                    <th scope="col">Activity</th>
                    <th scope="col">Unique entries</th>
                    <th scope="col">Registered users</th>
                </tr>
                </thead>

                <tbody>
                <?php
                foreach ($data['monthlyGrouped'] as $month => $activity) {
                    echo '<tr>';
                    echo "<td>$month</td>";
                    echo "<td>" . $activity['total'] . "</td>";
                    echo "<td>" . $activity['totalUnique'] . "</td>";
                    echo "<td>" . $activity['totalRegistered'] . "</td>";
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
