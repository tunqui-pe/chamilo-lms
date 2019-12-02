<?php

require_once '../../config.php';

$htmlHeadXtra[] = '<link rel="stylesheet" type="text/css" href="'.api_get_path(
        WEB_PLUGIN_PATH
    ).'buycourses/resources/css/style.css"/>';

$plugin = BuyCoursesPlugin::create();
$urlCatalog = api_get_path(WEB_PLUGIN_PATH).'buycourses/src/session_catalog.php';
$myCourses = api_get_path(WEB_PATH).'user_portal.php';
$template = new Template($plugin->get_lang('SuccessfulPurchase'));

if(isset($_POST['token_ws'])){
    $response = $_POST['response'];
    $token = $_POST['token_ws'];
    $template->assign('response', $response);
}

$template->assign('url_catalog', $urlCatalog);
$template->assign('my_courses', $myCourses);
$template->assign('title', $plugin->get_lang('Congratulations'));
$content = $template->fetch('buycourses/view/transbank/congratulations.tpl');

$template->assign('content', $content);
$template->display_one_col_template();
