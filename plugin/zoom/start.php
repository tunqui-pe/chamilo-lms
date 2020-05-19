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

$courseInfo = api_get_course_info();

$isAdmin = api_is_platform_admin();

$action = isset($_GET['action']) ? $_GET['action'] : null;
$enable = $plugin->get('zoom_enabled') == 'true';
$idCourse = $courseInfo['real_id'];
if ($enable) {
    if (api_is_platform_admin()) {

        $idRoomAssociate = $plugin->getIdRoomAssociateCourse($idCourse);

        if($idRoomAssociate){
            $roomInfo = $plugin->getRoomInfo($idRoomAssociate);
            $tpl->assign('room', $roomInfo);
        }

        $listRooms = $plugin->listZooms();

        $list = [];

        foreach ($listRooms as $room){
            $list[$room['id']] = $room['room_name'].' - '.$room['room_id'];
        }

        //create form
        $form = new FormValidator(get_lang('Search'));
        $form->addHeader($plugin->get_lang('ZoomVideoConferencingAccess'));
        $form->addHidden(
            'action',
                'associate'
            );
        $form->addSelect(
            'id_room',
            [
                $plugin->get_lang('ListRoomsAccounts'),
                $plugin->get_lang('ListRoomsAccountsHelp')
            ],
            $list,
            [
                'title'=>$plugin->get_lang('ListRoomsAccounts')
            ]
        );
        $form->addButtonSave($plugin->get_lang('AssociateRoomCourse'));

        if ($form->validate()) {
            $values = $form->exportValues();
            $idRoom = $values['id_room'];
            $res = $plugin->associateRoomCourse($idCourse,$idRoom);
        }

        $tpl->assign('form_zoom', $form->returnForm());
    }
}

$urlListRoom = api_get_path(WEB_PLUGIN_PATH).'zoom/list.php?action=list';


$tpl->assign('course', $courseInfo);
$tpl->assign('message', $message);
$tpl->assign('is_admin', $isAdmin);
$tpl->assign('url_list_room', $urlListRoom);
$content = $tpl->fetch('zoom/view/start.tpl');
$tpl->assign('content', $content);
$tpl->display_one_col_template();