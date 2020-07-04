<?php

require_once __DIR__.'/config.php';

$plugin = DiskAlertPlugin::create();
$isAdmin = api_is_platform_admin();

$action = isset($_GET['action']) ? $_GET['action'] : null;
$message = null;
$htmlHeadXtra[] = api_get_js_simple(api_get_path(WEB_LIBRARY_JS_PATH).'chartjs/Chart.min.js');
$htmlHeadXtra[] = '<link rel="stylesheet" type="text/css" href="'.api_get_path(
        WEB_PLUGIN_PATH
    ).'diskalert/assets/style.css"/>';

if ($isAdmin) {

    $infoSpace = $plugin->getInfoDisk();
    $records = $plugin->getStatusDisk();
    $enableAlertEmail = $plugin->get('alerts_email_enabled') == 'true';
    $percentAlertDisk = $plugin->get('alerts_percent_disk');

    if ($action) {
        switch ($action) {
            case 'delete':
                $idRecords = isset($_GET['id']) ? $_GET['id'] : null;
                $res = $plugin->deleteStatusDisk($idRecords);
                if ($res) {
                    Display::addFlash(
                        Display::return_message('El registro se a borrado correctamente.')
                    );
                    $url = api_get_path(WEB_PLUGIN_PATH).'diskalert/admin.php';
                    header('Location: '.$url);
                    exit;
                }
                break;
        }
    }
}
$tpl = new Template($plugin->get_lang('DiskAlertPlugin'));
$tpl->assign('message', $message);
$tpl->assign('info', $infoSpace);
$tpl->assign('alert_email', $enableAlertEmail);
$tpl->assign('percent_disk', $percentAlertDisk);
$tpl->assign('records', $records);
$content = $tpl->fetch('diskalert/views/alerts_star.tpl');
$tpl->assign('content', $content);
$tpl->display_one_col_template();
