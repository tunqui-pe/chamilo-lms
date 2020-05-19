<?php
/**
 * This script initiates a video conference session, calling the Zoom Conector API.
 */
require_once __DIR__.'/../../vendor/autoload.php';

$course_plugin = 'zoom'; //needed in order to load the plugin lang variables
require_once __DIR__.'/config.php';
api_protect_admin_script();

$htmlHeadXtra[] = '<link rel="stylesheet" type="text/css" href="'.api_get_path(
        WEB_PLUGIN_PATH
    ).'zoom/resources/css/style.css"/>';

$plugin = ZoomPlugin::create();

$userId = api_get_user_id();
$tool_name = $plugin->get_lang('tool_title');
$tpl = new Template($tool_name);

$isAdmin = api_is_platform_admin();
$message =  null;
$courseInfo = api_get_course_info();

$action = isset($_GET['action']) ? $_GET['action'] : null;
$enable = $plugin->get('zoom_enabled') == 'true';

if($enable){
    if (api_is_platform_admin()) {

        //create form
        $form = new FormValidator(
            'add_room',
            'post',
            api_get_self().'?action='.Security::remove_XSS($_GET['action']).'&'.api_get_cidreq()
        );
        $form->addText(
            'room_name',
            [
                $plugin->get_lang('RoomNameZoom'),
                $plugin->get_lang('RoomNameZoomHelp')
            ],
            true,
            [
                'title'=>$plugin->get_lang('MeetingIDZoomHelp')
            ]
        );
        $form->addText(
            'room_id',
            [
                $plugin->get_lang('MeetingIDZoom'),
                $plugin->get_lang('MeetingIDZoomHelp')
            ],
            true,
            [
                'title'=>$plugin->get_lang('MeetingIDZoomHelp')
            ]
        );
        $form->addText(
            'room_url',
            [
                $plugin->get_lang('InstantMeetingURL'),
                $plugin->get_lang('InstantMeetingURLHelp')
            ],
            true,
            [
                'title'=>$plugin->get_lang('InstantMeetingURLHelp')
            ]
        );
        $form->addText(
            'room_pass',
            [
                $plugin->get_lang('HostKey'),
                $plugin->get_lang('HostKeyHelp')
            ],
            false,
            [
                'title'=>$plugin->get_lang('HostKeyHelp')
            ]
        );
        $form->addText(
            'zoom_mail',
            [
                $plugin->get_lang('AccountEmailZoom'),
                $plugin->get_lang('AccountEmailZoomHelp')
            ],
            true,
            [
                'title'=>$plugin->get_lang('AccountEmailZoomHelp')
            ]
        );
        $form->addText(
            'zoom_pass',
            [
                $plugin->get_lang('Password'),
                $plugin->get_lang('PasswordZoomHelp')
            ],
            true,
            [
                'title'=>$plugin->get_lang('PasswordZoomHelp')
            ]
        );
        $form->addButtonSave($plugin->get_lang('AddRoomZoom'));

        if ($action) {
            switch ($action) {
                case 'add':
                    $tpl->assign('form_room', $form->returnForm());

                    if ($form->validate()) {

                        $values = $form->exportValues();
                        $res = $plugin->saveRoom($values);

                        if ($res) {
                            $url = api_get_path(WEB_PLUGIN_PATH).'zoom/list.php?action=list';
                            header('Location: '.$url);
                        }
                    }

                    break;
                case 'list':
                    $zooms = $plugin->listZooms();
                    $tpl->assign('zooms', $zooms);
                    break;
            }
        }

    }
}

$actionLinks = '';
if (api_is_platform_admin()) {
    $actionLinks .= Display::toolbarButton(
        $plugin->get_lang('AddRoomZoom'),
        api_get_path(WEB_PLUGIN_PATH).'zoom/list.php?action=add'
    );

    $tpl->assign(
        'actions',
        Display::toolbarAction('toolbar', [$actionLinks])
    );
}

$tpl->assign('message', $message);
$content = $tpl->fetch('zoom/view/list.tpl');
$tpl->assign('content', $content);
$tpl->display_one_col_template();