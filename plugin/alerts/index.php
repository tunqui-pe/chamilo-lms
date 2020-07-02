<?php

require_once __DIR__.'/config.php';

$plugin = AlertsPlugin::create();

$freeSpace = $plugin->getDiskTotalSpaceFree();
$totalSpace = $plugin->getDiskTotalSpace();

$tpl = new Template('Alerta Chamilo');
$tpl->assign('free_space', $freeSpace);
$tpl->assign('total_space', $totalSpace);
$content = $tpl->fetch('alerts/views/alerts_star.tpl');
$tpl->assign('content', $content);
$tpl->display_one_col_template();


