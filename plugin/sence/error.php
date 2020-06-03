<?php
/**
 * This script initiates a video conference session, calling the SENCE Conector API.
 */
require_once __DIR__.'/../../vendor/autoload.php';

require_once __DIR__.'/config.php';

$htmlHeadXtra[] = '<link rel="stylesheet" type="text/css" href="'.api_get_path(
        WEB_PLUGIN_PATH
    ).'sence/resources/css/style.css"/>';

$plugin = SencePlugin::create();

$tool_name = $plugin->get_lang('SynchronizationFailed');
$tpl = new Template($tool_name);

$courseInfo = api_get_course_info();

$tpl->assign('course', $courseInfo);
$content = $tpl->fetch('sence/view/sence_error.tpl');
$tpl->assign('content', $content);
$tpl->display_one_col_template();