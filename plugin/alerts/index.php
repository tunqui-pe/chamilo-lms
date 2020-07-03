<?php

require_once __DIR__.'/config.php';

$plugin = AlertsPlugin::create();
$isAdmin = api_is_platform_admin();

$htmlHeadXtra[] = api_get_js_simple(api_get_path(WEB_LIBRARY_JS_PATH).'chartjs/Chart.min.js');
$htmlHeadXtra[] = '<link rel="stylesheet" type="text/css" href="'.api_get_path(
        WEB_PLUGIN_PATH
    ).'alerts/assets/style.css"/>';

if ($isAdmin) {

    $infoSpace = $plugin->getInfoDisk();
    $records = $plugin->getStatusDisk();
    $enableAlertEmail = $plugin->get('alerts_email_enabled') == 'true';
    $percentAlertDisk = $plugin->get('alerts_percent_disk');

}
$tpl = new Template($plugin->get_lang('AlertPlugin'));
$tpl->assign('info', $infoSpace);
$tpl->assign('alert_email', $enableAlertEmail);
$tpl->assign('percent_disk', $percentAlertDisk);
$tpl->assign('records', $records);
$content = $tpl->fetch('alerts/views/alerts_star.tpl');
$tpl->assign('content', $content);
$tpl->display_one_col_template();


