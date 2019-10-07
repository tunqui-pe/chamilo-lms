<?php

$cidReset = true;

require_once __DIR__.'/../inc/global.inc.php';

api_protect_admin_script(true);

$sessionId = isset($_REQUEST['session_id']) ? (int) $_REQUEST['session_id'] : 0;

$tool_name = get_lang('ScheduledAnnouncements');

$object = new ScheduledAnnouncement();
$actions = null;
$actions .= '<div class="actions" style="margin-bottom:20px">';
$actions .= Display::url(
    Display::return_icon('back.png', get_lang('Back'), '', ICON_SIZE_MEDIUM),
    api_get_path(WEB_CODE_PATH).'session/scheduled_announcement.php?session_id='.$sessionId
);
$actions .= '</div>';

$result = Database::select(
    '*',
    $object->table,
    [
        'where' => ['session_id = ? ' => $sessionId ],
    ]
);


$sessions = [];
$sessions = [
    -1 => get_lang('All')
];

$sessionList = SessionManager::get_sessions_list();
if($sessionList){
    foreach ($sessionList as $item){
        $sessions[$item['id']] = $item['name'];
    }
}
unset($sessions[$sessionId]);


$list = [];
$options = [
    -1 => get_lang('All')
];
if ($result) {
    foreach ($result as &$item) {
        $options[$item['id']] = $item['subject'];
    }

    $form = new FormValidator(
        'announcement_copy',
        'post',
        api_get_self().'?session_id='.$sessionId.api_get_cidreq()
        );
    $form->addSelect(
        'announcement',
        get_lang('Announcements'),
        $options
    );
    $form->addSelect(
        'session',
        get_lang('SessionsList'),
        $sessions
    );
    $form->addButtonSave(get_lang('CopyScheduledAnnouncements'));
    $content = $form->returnForm();

} else {
    $content = Display::return_message(get_lang('NoScheduledAnnouncements'));
}

if ($form->validate()) {
    $values = $form->getSubmitValues();
    if (!empty($values['session'])) {

        if($values['announcement'] == '-1'){
            $announcement = Database::select(
                '*',
                $object->table,
                [
                    'where' => ['session_id = ? ' => $sessionId ],
                ]
            );
        } else {
            $announcement = Database::select(
                '*',
                $object->table,
                [
                    'where' => ['id = ? ' => $values['announcement'] ],
                ]
            );
        }

        //Cargamos la lista de anuncios a copiar
        $listInsert = [];
        if ($announcement) {
            foreach ($announcement as &$item) {
                $listInsert[] = [
                    'subject' => $item['subject'],
                    'message' => $item['message'],
                    'date' => $item['date'],
                    'sent' => '0',
                    'session_id' => $values['session']
                ];

            }
            foreach ($listInsert as $params) {
                //insertamos en la base de datos
                if (!empty($params)) {
                    $id = Database::insert($object->table, $params);
                    if (is_numeric($id)) {
                        $content= Display::return_message(get_lang('CopyConfirmation'));
                    }
                }
            }

        }


    }
}

$tpl = new Template($tool_name);
$tpl->assign('actions', $actions);
$tpl->assign('content', $content);
$tpl->display_one_col_template();