<?php
/**
 * This script initiates a video conference session, calling the SENCE Conector API.
 */
require_once __DIR__.'/../../vendor/autoload.php';

$course_plugin = 'sence'; //needed in order to load the plugin lang variables
require_once __DIR__.'/config.php';

$htmlHeadXtra[] = '<link rel="stylesheet" type="text/css" href="'.api_get_path(
        WEB_PLUGIN_PATH
    ).'sence/resources/css/style.css"/>';

$plugin = SencePlugin::create();

$tool_name = $plugin->get_lang('tool_title');
$tpl = new Template($tool_name);

$message =  null;

$courseInfo = api_get_course_info();

$action = isset($_GET['action']) ? $_GET['action'] : null;
$enable = $plugin->get('sence_enabled') == 'true';

$isTeacher = api_is_teacher();
$isStudent = api_is_student();
$isAdmin = api_is_course_admin();



if ($enable) {
    if ($isAdmin) {

        $urlAdd = api_get_path(WEB_PLUGIN_PATH).'sence/associate.php?action=add&'.api_get_cidreq();
        $senceInfo = $plugin->getSenceInfo($courseInfo['real_id']);

        if($senceInfo){
            $urlEdit = api_get_path(WEB_PLUGIN_PATH).'sence/associate.php?action=edit&'.api_get_cidreq();
            $urlDelete = api_get_path(WEB_PLUGIN_PATH).'sence/associate.php?action=delete&id_sence='.$senceInfo['id'].'&'.api_get_cidreq();
            $tpl->assign('sence', $senceInfo);
            $tpl->assign('url_edit_sence', $urlEdit);
            $tpl->assign('url_delete_sence', $urlDelete);
        } else {
            $tpl->assign('url_add_sence', $urlAdd);
        }

        switch ($action) {
            case 'add':
                break;

            case 'edit':
                break;

            default :
                break;

        }
    }
}




$tpl->assign('course', $courseInfo);
$tpl->assign('message', $message);
$content = $tpl->fetch('sence/view/sence_start.tpl');
$tpl->assign('content', $content);
$tpl->display_one_col_template();