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
$isAdmin = api_is_platform_admin();
$actionLinks = '';

if ($enable) {

    if ($isAdmin || $isTeacher) {

        $actionLinks .= Display::url(
            Display::return_icon('back.png', get_lang('Back'), [], ICON_SIZE_MEDIUM),
            api_get_path(WEB_PLUGIN_PATH).'sence/start.php?'.api_get_cidreq()
        );
        $actionLinks .= Display::url(
            Display::return_icon('excel.png', get_lang('GenerateReport'), [], ICON_SIZE_MEDIUM),
            api_get_path(WEB_PLUGIN_PATH).'sence/list.php?action=export&'.api_get_cidreq()
        );

        $tpl->assign(
            'actions',
            Display::toolbarAction('toolbar', [$actionLinks])
        );

        switch ($action) {
            case 'list':
                $lists = $plugin->getLogsHistory($courseInfo['real_id']);
                $tpl->assign('lists', $lists);
                break;

            case 'export':
                $columns[] = [
                    get_lang('Number'),
                    $plugin->get_lang('IDCourse'),
                    $plugin->get_lang('IDUser'),
                    $plugin->get_lang('Username'),
                    $plugin->get_lang('Firstname'),
                    $plugin->get_lang('Lastname'),
                    $plugin->get_lang('CodeSence'),
                    $plugin->get_lang('MultiAction'),
                    $plugin->get_lang('SessionSence'),
                    $plugin->get_lang('CodeCourse'),
                    $plugin->get_lang('RunStudentSence'),
                    $plugin->get_lang('DateLoginSence'),
                    $plugin->get_lang('TimeZoneSence'),
                    $plugin->get_lang('TrainingLine'),
                    $plugin->get_lang('TypeLogin'),
                    $plugin->get_lang('TypeError'),
                    $plugin->get_lang('DetailsError'),

                ];
                $logs = $plugin->getLogsHistory($courseInfo['real_id']);

                $lists = array_merge($columns,$logs);

                if (!empty($lists)) {
                    $archiveFile = 'export_login_'.api_get_local_time();
                    Export::arrayToXls($lists, $archiveFile);
                }
                break;
        }
    }
}


$tpl->assign('message', $message);
$content = $tpl->fetch('sence/view/sence_list.tpl');
$tpl->assign('content', $content);
$tpl->display_one_col_template();
