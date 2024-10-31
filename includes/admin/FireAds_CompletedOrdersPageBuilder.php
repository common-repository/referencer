<?php

defined('ABSPATH') or die('No access!');

class FireAds_CompletedOrdersPageBuilder
{
    private $completedOrdersRetriever;
    private $dateRangeRetrievier;
    private $ordersPaginator;

    public function __construct(
        FireAds_DateRangeRetrievier $dateRangeRetrievier,
        FireAds_CompletedOrdersRetriever $completedOrdersRetriever,
        FireAds_OrdersPaginator $ordersPaginator
    )
    {
        $this->dateRangeRetrievier = $dateRangeRetrievier;
        $this->completedOrdersRetriever = $completedOrdersRetriever;
        $this->ordersPaginator = $ordersPaginator;
    }

    public function buildPage()
    {
        $status = (isset($_GET['status'])) ? $_GET['status'] : null;
        $dateFrom = $this->dateRangeRetrievier->getFromDate();
        $dateTo = $this->dateRangeRetrievier->getToDate();
        $allOrdersCount = $this->ordersPaginator->getAllOrdersCount($status, $dateFrom, $dateTo);
        $pagesCount = $this->ordersPaginator->getPagesCount($allOrdersCount);
        $currentPage = $this->ordersPaginator->getCurrentPage();
        $allOrders = $this->completedOrdersRetriever->getCompletedOrdersFromDateRange($status, $dateFrom, $dateTo, $currentPage);
        $allOrdersCount = $this->ordersPaginator->getAllOrdersCount($status, $dateFrom, $dateTo);

        function isStatusSelected($key)
        {
            if (!isset($_GET['status'])) {
                return $key === 'all';
            }

            return $_GET['status'] === $key;
        }

        ?>
        <div class="wrap">
            <h1><?php _e('FireAds Orders') ?></h1>
            <div class="settings-wrapper">
                <form id="form-date" class="flex-column">
                    <div class="wrapper-status flex-centered flex-column">
                        <label for="fireads-purchases-status">Status:</label>
                        <select id="fireads-purchases-status">
                            <option value="" <?php echo isStatusSelected('all') ? 'selected' : '' ?>>
                                All
                            </option>
                            <?php foreach (wc_get_order_statuses() as $key => $value): ?>
                                <option value="<?php echo substr($key, 3); ?>" <?php echo isStatusSelected(substr($key, 3)) ? 'selected' : '' ?>>
                                    <?php echo $value; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="flex-ended">
                        <div class="flex-centered flex-column">
                            <label for="fireads-purchases-date-from">From:</label>
                            <input type="text" id="fireads-purchases-date-from" class="input-date" placeholder="Date" data-input
                                   value="<?php echo ($dateFrom) ? $dateFrom : "" ?>">
                        </div>
                        <div class="wrapper-to-date flex-centered flex-column">
                            <label for="fireads-purchases-date-to">To:</label>
                            <input type="text" id="fireads-purchases-date-to" class="input-date" placeholder="Date" data-input
                                   value="<?php echo ($dateTo) ? $dateTo : "" ?>">
                        </div>
                        <button id="btn-search"
                                type="submit"
                                class="last-page button btn-date">
                            <span class="screen-reader-text">Search</span>
                            <span aria-hidden="true">Search</span>
                        </button>
                    </div>
                </form>
                <div class="tablenav-pages">
                    <span class="displaying-num"><?php echo $allOrdersCount; ?> positions</span>
                    <span class="pagination-links">
                       <?php if ($currentPage > 1): ?>
                           <button id="btn-page-first" class="next-page button">
                               <span class="screen-reader-text">First page</span>
                               <span aria-hidden="true">«</span>
                           </button>
                           <button id="btn-page-previous" class="last-page button">
                               <span class="screen-reader-text">Previous page</span>
                               <span aria-hidden="true">‹</span>
                            </button>
                       <?php else: ?>
                           <span class="tablenav-pages-navspan button disabled" aria-hidden="true">«</span>
                           <span class="tablenav-pages-navspan button disabled" aria-hidden="true">‹</span>
                       <?php endif; ?>
                        <span class="paging-input">
                            <label for="current-page-selector" class="screen-reader-text">Current page</label>
                            <input class="current-page" id="current-page-input" type="text" name="paged"
                                   value="<?php echo $currentPage; ?>" size="1" aria-describedby="table-paging">
                            <span class="tablenav-paging-text">
                                out of
                                <span id="pages-count" class="total-pages"><?php echo $pagesCount; ?></span>
                            </span>
                         </span>
                        <?php if ($currentPage < $pagesCount): ?>
                            <button id="btn-page-next" class="next-page button">
                                <span class="screen-reader-text">Next page</span>
                                <span aria-hidden="true">›</span>
                           </button>
                            <button id="btn-page-last" class="last-page button">
                                <span class="screen-reader-text">Last page</span>
                                <span aria-hidden="true">»</span>
                            </button>
                        <?php else: ?>
                            <span class="tablenav-pages-navspan button disabled" aria-hidden="true">›</span>
                            <span class="tablenav-pages-navspan button disabled" aria-hidden="true">»</span>
                        <?php endif; ?>
                    </span>
                </div>
            </div>

            <table class="wp - list-table widefat fixed striped posts">
                <thead>
                <tr>
                    <th scope="col">Order</th>
                    <th scope="col">Date</th>
                    <th scope="col">Total</th>
                </tr>
                </thead>

                <tbody>
                <?php foreach ($allOrders as $order): ?>
                    <tr>
                        <td>
                            <a class="order-view"
                               href="<?php echo get_admin_url() . "post.php?action=edit&post={$order->get_id()}" ?>">
                                <strong><?php echo "#{$order->get_id()}" ?></strong>
                            </a>
                        </td>
                        <td><?php echo $order->get_date_created()->format('M d, Y') ?></td>
                        <td>
                            <div class="currency-wrapper">
                                <span>Currency: <?php echo $order->get_currency() ?></span>
                                <span><?php echo get_woocommerce_currency_symbol($order->get_currency()) . $order->get_total() ?></span>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php
    }
}
