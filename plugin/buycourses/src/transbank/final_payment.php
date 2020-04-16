<?php

/**
 *
 * @package chamilo.plugin.buycourses
 *
 */

require_once '../../config.php';

$htmlHeadXtra[] = '<link rel="stylesheet" type="text/css" href="'.api_get_path(
        WEB_PLUGIN_PATH
    ).'buycourses/resources/css/style.css"/>';

$plugin = BuyCoursesPlugin::create();
$urlCatalog = api_get_path(WEB_PLUGIN_PATH).'buycourses/src/session_catalog.php';
$myCourses = api_get_path(WEB_PATH).'user_portal.php';

$template = new Template($plugin->get_lang('SuccessfulPurchase'));
$template->assign('url_catalog', $urlCatalog);
$template->assign('my_courses', $myCourses);

//If the purchase is canceled before placing the RUT
$response = false;

if(isset($_POST['status'])){
    if($_POST['status'] == '2'){
        //If the purchase completes all the steps
        $response = false;
    }
} else {
    if(isset($_POST['token_ws'])){
        $response = true;
    }
}

//If the purchase is canceled after placing the RUT

if(isset($_POST['TBK_ORDEN_COMPRA'])){
    $byOrderReference = $_POST['TBK_ORDEN_COMPRA'];
    $sale = $plugin->getSaleReference($byOrderReference);
    $sale = $plugin->getSaleReference($byOrderReference);
    $plugin->cancelSale($sale['id']);
    $response = false;
}

$template->assign('response', $response);

$content = $template->fetch('buycourses/view/transbank/congratulations.tpl');

$template->assign('content', $content);
$template->display_one_col_template();
