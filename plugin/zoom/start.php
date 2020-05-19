<?php
/**
 * This script initiates a video conference session, calling the Zoom Conector API.
 */
require_once __DIR__.'/../../vendor/autoload.php';

$course_plugin = 'zoom'; //needed in order to load the plugin lang variables
require_once __DIR__.'/config.php';

$htmlHeadXtra[] = '<link rel="stylesheet" type="text/css" href="'.api_get_path(
        WEB_PLUGIN_PATH
    ).'zoom/resources/css/style.css"/>';

$plugin = ZoomPlugin::create();

$tool_name = $plugin->get_lang('tool_title');
$tpl = new Template($tool_name);
$message =  null;
api_protect_admin_script();
$courseInfo = api_get_course_info();
$isAdmin = api_is_platform_admin();

$action = isset($_GET['action']) ? $_GET['action'] : null;
$enable = $plugin->get('zoom_enabled') == 'true';

if ($enable) {
    if (api_is_platform_admin()) {

        //create form
        $form = new FormValidator(get_lang('Search'));
        $form->addHeader($plugin->get_lang('ZoomVideoConferencingAccess'));
        $form->addText(
            'code_course',
            [
                $plugin->get_lang('CodeCourse'),
                $plugin->get_lang('CodeCourseHelp')
            ],
            true,
            [
                'value' => $courseInfo['official_code']
            ]
        )->freeze();
        $form->addText(
            'code_sence_course',
            [
                $plugin->get_lang('CodeSence'),
                $plugin->get_lang('CodeSenceHelp')
            ],
            true,
            [
                'title'=>$plugin->get_lang('CodeSenceHelp')
            ]
        );
        $form->addButtonSave($plugin->get_lang('SaveCodeSence'));

        if ($form->validate()) {

        }

    }
}

$urlListRoom = api_get_path(WEB_PLUGIN_PATH).'zoom/list.php?action=list';

$tpl->assign('form_zoom', $form->returnForm());
$tpl->assign('course', $courseInfo);
$tpl->assign('message', $message);
$tpl->assign('is_admin', $isAdmin);
$tpl->assign('url_list_room', $urlListRoom);
$content = $tpl->fetch('zoom/view/start.tpl');
$tpl->assign('content', $content);
$tpl->display_one_col_template();