<?php
/**
 * This script initiates a video conference session, calling the Zoom Easy Conector API.
 */
require_once __DIR__.'/../../vendor/autoload.php';

$course_plugin = 'zoomeasy'; //needed in order to load the plugin lang variables
require_once __DIR__.'/config.php';

$htmlHeadXtra[] = '<link rel="stylesheet" type="text/css" href="'.api_get_path(
        WEB_PLUGIN_PATH
    ).'zoomeasy/resources/css/style.css"/>';

$plugin = ZoomEasyPlugin::create();

$userId = api_get_user_id();
$tool_name = $plugin->get_lang('tool_title');
$tpl = new Template($tool_name);

$isAdmin = api_is_platform_admin();
$isTeacher = api_is_teacher();
$message = null;
$courseInfo = api_get_course_info();
$viewCredentials = $plugin->get('view_credentials') == 'true';

$action = isset($_GET['action']) ? $_GET['action'] : null;
$enable = $plugin->get('zoomeasy_enabled') == 'true';
$actionLinks = '';

$iconAdd = Display::return_icon(
    'add.png',
    $plugin->get_lang('AddRoomZoomEasy'),
    [],
    32
);


if ($enable) {
    if ($isAdmin || $isTeacher) {

        if ($action) {
            switch ($action) {
                case 'delete':
                    $idRoom = isset($_GET['id_room']) ? $_GET['id_room'] : null;
                    $res = $plugin->deleteRoom($idRoom);
                    if ($res) {
                        $url = api_get_path(WEB_PLUGIN_PATH).'zoomeasy/list.php?action=list';
                        header('Location: '.$url);
                    }
                    break;
                case 'add':
                    $actionLinks .= Display::url(
                        Display::return_icon('back.png', get_lang('Back'), [], ICON_SIZE_MEDIUM),
                        api_get_self().'?action=list&'.api_get_cidreq()
                    );

                    //create form
                    $form = new FormValidator(
                        'add_room',
                        'post',
                        api_get_self().'?action='.Security::remove_XSS($_GET['action']).'&'.api_get_cidreq()
                    );

                    $form->addHeader($plugin->get_lang('AddRoomZoomEasy'));

                    $form->addText(
                        'room_name',
                        [
                            $plugin->get_lang('RoomNameZoomEasy'),
                            $plugin->get_lang('RoomNameZoomEasyHelp'),
                        ],
                        true,
                        [
                            'title' => $plugin->get_lang('MeetingIDZoomEasyHelp'),
                        ]
                    );
                    $form->addText(
                        'room_id',
                        [
                            $plugin->get_lang('MeetingIDZoomEasy'),
                            $plugin->get_lang('MeetingIDZoomEasyHelp'),
                        ],
                        true,
                        [
                            'title' => $plugin->get_lang('MeetingIDZoomEasyHelp'),
                        ]
                    );
                    $form->addText(
                        'room_url',
                        [
                            $plugin->get_lang('InstantMeetingURL'),
                            $plugin->get_lang('InstantMeetingURLHelp'),
                        ],
                        true,
                        [
                            'title' => $plugin->get_lang('InstantMeetingURLHelp'),
                        ]
                    );
                    $form->addText(
                        'room_pass',
                        [
                            $plugin->get_lang('HostKey'),
                            $plugin->get_lang('HostKeyHelp'),
                        ],
                        false,
                        [
                            'title' => $plugin->get_lang('HostKeyHelp'),
                        ]
                    );

                    if (!$isAdmin) {
                        $typeRoom = 2;
                        $form->addHidden('type_room', $typeRoom);
                    } else {

                        $list = [
                            '1' => $plugin->get_lang('GeneralRoom'),
                            '2' => $plugin->get_lang('PersonalRoom'),
                        ];

                        $form->addSelect(
                            'type_room',
                            [
                                $plugin->get_lang('TypeRoom'),
                                $plugin->get_lang('TypeRoomHelp'),
                            ],
                            $list,
                            [
                                'title' => $plugin->get_lang('TypeRoom'),
                            ]
                        );
                        try {
                            $form->addRule('type_room', $plugin->get_lang('TypeRoomHelp'), 'required');
                        } catch (Exception $e) {
                            echo $e;
                        }

                    }

                    $form->addText(
                        'zoom_email',
                        [
                            $plugin->get_lang('AccountEmailZoomEasy'),
                            $plugin->get_lang('AccountEmailZoomEasyHelp'),
                        ],
                        false,
                        [
                            'title' => $plugin->get_lang('AccountEmailZoomEasyHelp'),
                        ]
                    );

                    try {
                        $form->addElement(
                            'password',
                            'zoom_pass',
                            [
                                $plugin->get_lang('PasswordZoomEasy'),
                                $plugin->get_lang('PasswordZoomEasyHelp'),
                            ],
                            [
                                'size' => 40,
                            ]
                        );
                    } catch (HTML_QuickForm_Error $e) {
                        echo $e;
                    }

                    $form->addButtonSave($plugin->get_lang('Add'));

                    $tpl->assign('form_room', $form->returnForm());
                    $tpl->assign('is_admin', $isAdmin);
                    $tpl->assign('is_teacher', $isTeacher);

                    if ($form->validate()) {

                        $values = $form->exportValues();
                        $res = $plugin->saveRoom($values);

                        if ($res) {
                            $url = api_get_path(WEB_PLUGIN_PATH).'zoomeasy/list.php?action=list';
                            header('Location: '.$url);
                        }
                    }
                    break;
                case 'edit':
                    $actionLinks .= Display::url(
                        Display::return_icon('back.png', get_lang('Back'), [], ICON_SIZE_MEDIUM),
                        api_get_self().'?action=list&'.api_get_cidreq()
                    );
                    $idRoom = isset($_GET['id_room']) ? (int)$_GET['id_room'] : 0;
                    $dataRoom = $plugin->getRoomInfo(Security::remove_XSS($_GET['id_room']));

                    //create form
                    $form = new FormValidator(
                        'edit_room',
                        'post',
                        api_get_self().'?action='.Security::remove_XSS($_GET['action']).'&'.api_get_cidreq()
                    );

                    $form->addHeader($plugin->get_lang('EditRoomZoomEasy'));

                    $form->addText(
                        'room_name',
                        [
                            $plugin->get_lang('RoomNameZoomEasy'),
                            $plugin->get_lang('RoomNameZoomEasyHelp'),
                        ],
                        true,
                        [
                            'title' => $plugin->get_lang('MeetingIDZoomEasyHelp'),
                        ]
                    );
                    $form->addText(
                        'room_id',
                        [
                            $plugin->get_lang('MeetingIDZoomEasy'),
                            $plugin->get_lang('MeetingIDZoomEasyHelp'),
                        ],
                        true,
                        [
                            'title' => $plugin->get_lang('MeetingIDZoomEasyHelp'),
                        ]
                    );
                    $form->addText(
                        'room_url',
                        [
                            $plugin->get_lang('InstantMeetingURL'),
                            $plugin->get_lang('InstantMeetingURLHelp'),
                        ],
                        true,
                        [
                            'title' => $plugin->get_lang('InstantMeetingURLHelp'),
                        ]
                    );
                    $form->addText(
                        'room_pass',
                        [
                            $plugin->get_lang('HostKey'),
                            $plugin->get_lang('HostKeyHelp'),
                        ],
                        false,
                        [
                            'title' => $plugin->get_lang('HostKeyHelp'),

                        ]
                    );

                    if (!$isAdmin) {
                        $typeRoom = 2;
                        $form->addHidden('type_room', null);
                    } else {

                        $list = [
                            '1' => $plugin->get_lang('GeneralRoom'),
                            '2' => $plugin->get_lang('PersonalRoom'),
                        ];

                        $form->addSelect(
                            'type_room',
                            $plugin->get_lang('TypeRoom'),
                            $list,
                            [
                                'title' => $plugin->get_lang('TypeRoom'),
                            ]
                        );
                    }

                    $form->addText(
                        'zoom_email',
                        [
                            $plugin->get_lang('AccountEmailZoomEasy'),
                            $plugin->get_lang('AccountEmailZoomEasyHelp'),
                        ],
                        false,
                        [
                            'title' => $plugin->get_lang('AccountEmailZoomEasyHelp'),
                        ]
                    );

                    try {
                        $form->addElement(
                            'password',
                            'zoom_pass',
                            [
                                $plugin->get_lang('PasswordZoomEasy'),
                                $plugin->get_lang('PasswordZoomEasyHelp'),
                            ],
                            [
                                'size' => 40,
                            ]
                        );
                    } catch (HTML_QuickForm_Error $e) {
                        echo $e;
                    }


                    $form->addHidden('id', $idRoom);
                    $form->addButtonSave($plugin->get_lang('Save'));

                    $form->setDefaults($dataRoom);

                    if ($form->validate()) {

                        $values = $form->exportValues();

                        $res = $plugin->updateRoom($values);

                        if ($res) {
                            $url = api_get_path(WEB_PLUGIN_PATH).'zoomeasy/list.php?action=list';
                            header('Location: '.$url);
                        }
                    }

                    $tpl->assign('form_room', $form->returnForm());


                    break;

                case 'list':

                    $actionLinks .= Display::url(
                        Display::return_icon('back.png', get_lang('Back'), [], ICON_SIZE_MEDIUM),
                        'start.php?action=list&'.api_get_cidreq()
                    );

                    $actionLinks .= Display::url(
                        $iconAdd
                        ,
                        api_get_path(WEB_PLUGIN_PATH).'zoomeasy/list.php?action=add&'.api_get_cidreq()
                    );

                    $zooms = [];

                    if ($isAdmin) {
                        $listRoomsAdmin = $plugin->listZoomEasysAdmin(1);
                        $listRoomsUser = $plugin->listZoomEasys(2, $userId, false);
                        if (is_array($listRoomsAdmin) && is_array($listRoomsUser)) {
                            $zooms = array_merge($listRoomsAdmin, $listRoomsUser);
                        } else {
                            $zooms = $plugin->listZoomEasys(2, $userId, false);
                        }
                    } else {
                        $zooms = $plugin->listZoomEasys(2, $userId, false);
                    }

                    //$zooms = $plugin->listZoomEasys($typeRoom, $userId);
                    $tpl->assign('zooms', $zooms);
                    break;
            }
        }

    }
}


if ($isAdmin || $isTeacher) {

    $tpl->assign(
        'actions',
        Display::toolbarAction('toolbar', [$actionLinks])
    );
}

$tpl->assign('message', $message);
$tpl->assign('view_pass', $viewCredentials);
$content = $tpl->fetch('zoomeasy/view/list.tpl');
$tpl->assign('content', $content);
$tpl->display_one_col_template();
