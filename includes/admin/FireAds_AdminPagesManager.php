<?php

defined('ABSPATH') or die('No access!');

class FireAds_AdminPagesManager
{
    private $settingsPageBuilder;
    private $completedOrdersPageBuilder;
    private $ordersPageOverwriter;
    private $activityPageBuilder;

    public function __construct(
        FireAds_ActivityPageBuilder $activityPageBuilder,
        FireAds_CompletedOrdersPageBuilder $completedOrdersPageBuilder,
        FireAds_SettingsPageBuilder $settingsPageBuilder,
        FireAds_OrdersPageOverwriter $ordersPageOverwriter
    )
    {
        $this->activityPageBuilder = $activityPageBuilder;
        $this->completedOrdersPageBuilder = $completedOrdersPageBuilder;
        $this->settingsPageBuilder = $settingsPageBuilder;
        $this->ordersPageOverwriter = $ordersPageOverwriter;
    }

    public function attachAdminHooks()
    {
        add_action('admin_menu', array($this, 'addFireAdsPages'));
        add_action('admin_init', array($this->settingsPageBuilder, 'initReferenceSettingsPage'));
        add_filter('manage_edit-shop_order_columns', array($this->ordersPageOverwriter, 'addFireAdsColumn'));
        add_action(
            'manage_shop_order_posts_custom_column',
            array($this->ordersPageOverwriter, 'fillFireAdsColumn')
        );
    }

    public function addFireAdsPages()
    {
        add_menu_page(
            'FireAds',
            'FireAds',
            'manage_referencer',
            'fireads',
            null,
            "data:image/svg+xml,%3C%3Fxml version='1.0' standalone='no'%3F%3E%3C!DOCTYPE svg PUBLIC '-//W3C//DTD SVG 20010904//EN' 'http://www.w3.org/TR/2001/REC-SVG-20010904/DTD/svg10.dtd'%3E%3Csvg xmlns='http://www.w3.org/2000/svg' version='1.0' width='21px' height='21px' viewBox='0 0 674.000000 678.000000' preserveAspectRatio='xMidYMid meet' fill='%23f0f5fa'%3E%3Cmetadata%3E%0ACreated by potrace 1.15, written by Peter Selinger 2001-2017%0A%3C/metadata%3E%3Cg transform='translate(0.000000,678.000000) scale(0.100000,-0.100000)' fill='%23f0f5fa' stroke='none'%3E%3Cpath d='M4199 6528 l80 -93 -137 -6 c-250 -12 -372 -63 -587 -245 -80 -68 -143 -149 -203 -261 -56 -107 -172 -395 -163 -406 4 -4 265 -6 580 -5 l573 3 257 535 c142 295 260 543 264 553 6 16 -18 17 -369 17 l-375 0 80 -92z'/%3E%3Cpath d='M3590 6550 c-94 -138 -149 -156 -623 -200 -144 -14 -236 -39 -342 -92 -289 -145 -585 -503 -635 -768 -21 -112 -8 -365 26 -478 5 -18 3 -21 -9 -16 -23 8 -98 129 -138 219 -18 41 -36 75 -40 75 -23 0 -126 -402 -136 -530 -6 -82 7 -301 36 -585 10 -99 17 -182 15 -184 -2 -2 -25 40 -50 95 -62 132 -127 332 -154 477 -19 97 -25 116 -36 105 -22 -23 -526 -1000 -1148 -2229 l-276 -546 46 -7 c26 -3 299 -6 608 -6 l561 0 431 826 431 827 -24 69 c-70 200 -40 458 78 689 89 173 189 287 400 455 134 107 195 172 209 223 13 46 13 118 1 126 -6 3 -42 -23 -81 -57 -110 -97 -156 -126 -206 -134 -93 -14 -184 39 -230 133 -25 49 -29 70 -29 138 0 95 21 142 93 211 65 63 111 74 300 74 85 0 171 5 191 11 88 26 162 109 176 196 l7 42 -41 -16 c-79 -31 -133 0 -161 94 -11 34 -10 51 0 90 21 74 74 119 215 182 204 90 278 129 338 176 58 46 136 143 181 225 27 51 68 150 61 150 -2 0 -22 -27 -45 -60z'/%3E%3Cpath d='M3216 4678 c-122 -214 -559 -1058 -551 -1066 4 -4 407 -9 897 -12 l890 -5 35 -85 c103 -250 461 -1103 565 -1347 l120 -283 704 0 704 0 -24 53 c-18 40 -889 1874 -1261 2655 l-64 132 -995 0 -995 0 -25 -42z'/%3E%3Cpath d='M2434 2893 c-32 -60 -165 -310 -296 -558 l-238 -450 1322 -3 c727 -1 1323 0 1326 3 5 5 -71 195 -308 772 l-139 338 -805 3 -804 2 -58 -107z'/%3E%3C/g%3E%3C/svg%3E%0A",
            57
        );

        $purchasePage = add_submenu_page(
            'fireads',
            'FireAds Orders',
            'Orders',
            'manage_options',
            'fireads-completed-orders',
            array($this->completedOrdersPageBuilder, 'buildPage')
        );
        add_action("load-$purchasePage", [$this, 'loadPurchasesJSandCSS']);

        add_submenu_page(
            'fireads',
            'FireAds Activity',
            'Activity',
            'manage_options',
            'fireads-activity',
            array($this->activityPageBuilder, 'buildPage')
        );

        add_submenu_page(
            'fireads',
            'FireAds Settings',
            'Settings',
            'manage_options',
            'fireads-configure',
            array($this->settingsPageBuilder, 'createSettingsPage')
        );
    }

    public function loadPurchasesJSandCSS()
    {
        add_action('admin_enqueue_scripts', [$this, 'enqueuePurchasesJSandCSSn']);
    }

    public function enqueuePurchasesJSandCSSn()
    {
        wp_enqueue_script('fireads-flatpickr-js', plugin_dir_url(__FILE__) . '/../../../assets/js/flatpickr.min.js', '', '', true);
        wp_enqueue_script('fireads-page-completedOrders-js', plugin_dir_url(__FILE__) . '/../../../assets/js/pages/completed-orders.js', '', '', true);
        wp_enqueue_style('fireads-flatpickr-css', plugin_dir_url(__FILE__) . '/../../../assets/css/flatpickr.min.css');
        wp_enqueue_style('fireads-page-completedOrders-css', plugin_dir_url(__FILE__) . '/../../../assets/css/pages/completed-orders.css');
    }
}

