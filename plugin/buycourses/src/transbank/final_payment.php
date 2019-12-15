<?php

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
$userInfo = api_get_user_info();


if(isset($_POST['token_ws'])){
    $response = 0;
    if(isset($_POST['response'])){
        $response = $_POST['response'];
    }
    $token = $_POST['token_ws'];
    $template->assign('response', $response);

    if($response == 0){
        $template->assign('title', $plugin->get_lang('Congratulations'));
    } else {
        $template->assign('title', $plugin->get_lang('TransactionWasRejected'));
    }
} else {
    $template->assign('title', $plugin->get_lang('TransactionWasCanceled'));
}

$content = $template->fetch('buycourses/view/transbank/congratulations.tpl');

$template->assign('content', $content);
$template->display_one_col_template();
