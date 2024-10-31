<?php
/**
 * Plugin Name: FireAds Referencer
 * Description: Referencer connects your Woocoommerce shop to FireAds platform.
 * Version: 1.3.6
 */

defined('ABSPATH') or die('No access!');

require 'includes/FireAds_GlobalArrayConditioner.php';
require 'includes/FireAds_ActivationController.php';
require 'includes/FireAds_CookieManager.php';
require 'includes/FireAds_AuthActioner.php';
require 'includes/FireAds_AuthHandler.php';
require 'includes/FireAds_WpLoadHandler.php';
require 'includes/FireAds_RedirectActioner.php';
require 'includes/database/FireAds_ActivityDbManager.php';
require 'includes/FireAds_ActivityActioner.php';
require 'includes/FireAds_ReferenceKeyManager.php';
require 'includes/FireAds_OrderSubmitController.php';
require 'includes/FireAds_OrderStatusChangedController.php';
require 'includes/FireAds_PostbackSender.php';

$activationController = new FireAds_ActivationController();
register_activation_hook(__FILE__, [$activationController, 'activationListener']);

$globalArraysConditioner = new FireAds_GlobalArrayConditioner();
$cookieManager = new FireAds_CookieManager();
$referenceKeyManager = new FireAds_ReferenceKeyManager();
$activityDbManager = new FireAds_ActivityDbManager();
$activityActions = new FireAds_ActivityActioner($activityDbManager, $cookieManager);
$authActions = new FireAds_AuthActioner($globalArraysConditioner, $activityDbManager);

(new FireAds_WpLoadHandler(
    $globalArraysConditioner,
    new FireAds_RedirectActioner($cookieManager, $authActions, $activityActions),
    $activityActions
))->attachLoadHook();
(new FireAds_AuthHandler($authActions))->attachAuthHooks();
(new FireAds_OrderSubmitController($referenceKeyManager, $globalArraysConditioner))->attachOrderSubmitHook();
(new FireAds_OrderStatusChangedController(new FireAds_PostbackSender()))->attachOrderStatusChangedHook();

if (is_admin()) {
    require 'includes/admin/FireAds_AdminPagesManager.php';
    require 'includes/admin/FireAds_ActivityPageBuilder.php';
    require 'includes/admin/FireAds_ActivityPageDataBuilder.php';
    require 'includes/admin/FireAds_CompletedOrdersPageBuilder.php';
    require 'includes/admin/FireAds_DateRangeRetrievier.php';
    require 'includes/admin/FireAds_CompletedOrdersRetriever.php';
    require 'includes/admin/FireAds_OrdersPaginator.php';
    require 'includes/database/FireAds_OrdersDbManager.php';
    require 'includes/admin/FireAds_SettingsPageBuilder.php';
    require 'includes/admin/FireAds_OrdersPageOverwriter.php';

    $ordersDbManager = new FireAds_OrdersDbManager();
    (new FireAds_AdminPagesManager(
        new FireAds_ActivityPageBuilder(new FireAds_ActivityPageDataBuilder()),
        new FireAds_CompletedOrdersPageBuilder(
            new FireAds_DateRangeRetrievier(),
            new FireAds_CompletedOrdersRetriever(),
            new FireAds_OrdersPaginator()
        ),
        new FireAds_SettingsPageBuilder(),
        new FireAds_OrdersPageOverwriter()
    ))->attachAdminHooks();
}
