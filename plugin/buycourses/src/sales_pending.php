<?php
/**
 * List of pending payments of the Buy Courses plugin.
 *
 * @package chamilo.plugin.buycourses
 */
//Initialization
$cidReset = true;

require_once '../config.php';

api_protect_admin_script();
$plugin = BuyCoursesPlugin::create();

$paypalEnable = $plugin->get('paypal_enable');

$templateName = $plugin->get_lang('UnpaidPurchases');

$template = new Template($templateName);

$content = $template->fetch('buycourses/view/sales_pending.tpl');
$template->assign('header', $templateName);
$template->assign('content', $content);
$template->display_one_col_template();