<?php
/**
 * List of pending payments of the Buy Courses plugin.
 *
 * @package chamilo.plugin.buycourses
 */
//Initialization
$cidReset = true;

require_once '../config.php';

$plugin = BuyCoursesPlugin::create();
$userID = api_get_user_id();

$paypalEnable = $plugin->get('paypal_enable');
$templateName = $plugin->get_lang('UnpaidPurchases');

$sales = $plugin->getSaleListByStatus(0, $userID);

$template = new Template($templateName);
$template->assign('sales', $sales);
$content = $template->fetch('buycourses/view/sales_pending.tpl');
$template->assign('header', $templateName);
$template->assign('content', $content);
$template->display_one_col_template();