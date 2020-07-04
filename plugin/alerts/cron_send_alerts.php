<?php

require_once __DIR__.'/config.php';
$plugin = AlertsPlugin::create();
$enableAlertEmail = $plugin->get('alerts_email_enabled') == 'true';
$percentAlertDisk = $plugin->get('alerts_percent_disk');
//get info status disk
$infoStatus = $plugin->getInfoDisk();
$date = date('Y-m-d h:i:s', time());
//save status in data bases
$plugin->saveStatusDisk();

if($enableAlertEmail){
    $mailAdmin = api_get_setting('emailAdministrator');
    $nameAdmin = api_get_setting('administratorName');
    $messageTemplate = new Template();
    $messageTemplate->assign('date', $date);
    $messageTemplate->assign('info', $infoStatus);
    api_mail_html(
        $nameAdmin,
        $mailAdmin,
        "Alerta! de espacio en disco"." - " . api_get_setting('siteName'),
        $messageTemplate->fetch('alerts/views/message_alert.tpl')
    );
}