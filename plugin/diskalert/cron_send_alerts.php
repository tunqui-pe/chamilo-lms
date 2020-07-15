<?php

require_once __DIR__.'/config.php';
$plugin = DiskAlertPlugin::create();
$enableAlertEmail = $plugin->get('alerts_email_enabled') == 'true';
$percentAlertDisk = intval($plugin->get('alerts_percent_disk'));
$emailAlertDisk = $plugin->get('alerts_email_disk');
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
        $plugin->get_lang('AlertDiskSpace')." - " . api_get_setting('siteName'),
        $messageTemplate->fetch('diskalert/views/message_alert.tpl')
    );
    if(intval($infoStatus['used_percent']) >= $percentAlertDisk){
        if(!empty($emailAlertDisk)) {
            $messageTemplate = new Template();
            $messageTemplate->assign('date', $date);
            $messageTemplate->assign('info', $infoStatus);
            api_mail_html(
                $nameAdmin,
                $emailAlertDisk,
                $plugin->get_lang('UrgentAlertDiskSpace')." - ".api_get_setting('siteName'),
                $messageTemplate->fetch('diskalert/views/urgent_alert_message.tpl'),
                null,
                null,
                [],
                [],
                false,
                [],
                null,
                [$mailAdmin]
            );
        }
    }
}
