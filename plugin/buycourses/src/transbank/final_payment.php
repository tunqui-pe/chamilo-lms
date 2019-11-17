<?php

require_once '../../config.php';

$htmlHeadXtra[] = '<link rel="stylesheet" type="text/css" href="'.api_get_path(
        WEB_PLUGIN_PATH
    ).'buycourses/resources/css/style.css"/>';

$plugin = BuyCoursesPlugin::create();
$urlCatalog = api_get_path(WEB_PLUGIN_PATH).'plugin/buycourses/src/session_catalog.php';
$template = new Template($plugin->get_lang('SuccessfulPurchase'));
$template->assign('url_catalog', $urlCatalog);
$template->assign('title', $plugin->get_lang('Congratulations'));
$content = $template->fetch('buycourses/view/transbank/congratulations.tpl');

$template->assign('content', $content);
$template->display_one_col_template();
