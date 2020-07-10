<?php
/**
 * @author Alex Aragón <aragcar@gmail.com> Adding formvalidator support
 */
require_once __DIR__.'/../inc/global.inc.php';
require_once 'work.lib.php';

$this_section = SECTION_COURSES;
api_protect_course_script();

$is_allowed_to_edit = api_is_allowed_to_edit(null, true);
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : null;

$interbreadcrumb[] = [
    'url' => 'work.php?action=list&'.api_get_cidreq(),
    'name' => get_lang('StudentPublications'),
];
$tool_name = get_lang('AddCategory');

$idCourse = api_get_course_int_id();
$idSession = api_get_session_id();
$tpl = new Template($tool_name);
$form = new FormValidator(
    'category',
    'post',
    api_get_self().'?'.api_get_cidreq()
);
$id = isset($_REQUEST['cat']) ? $_REQUEST['cat'] : null;

switch ($action) {
    case 'add':
        $actions = Display::url(
            Display::return_icon(
                'back.png',
                get_lang('BackToWorksList'),
                null,
                ICON_SIZE_MEDIUM
            ),
            api_get_path(WEB_CODE_PATH).'work/category.php?action=list&'.api_get_cidreq()
        );

        // Form title
        $form->addElement('header', null, get_lang('AddCategory'));
        $form->addText('name', get_lang('Name'), true);

        $form->addElement('hidden', 'action', 'add_category');
        $form->addElement('hidden', 'c_id', $idCourse);
        $form->addElement('hidden', 'id_session', api_get_session_id());

        $form->addButtonSave(get_lang('Save'));

        $tpl->assign('form', $form->returnForm());

        break;
    case 'edit':

        $actions = Display::url(
            Display::return_icon(
                'back.png',
                get_lang('BackToWorksList'),
                null,
                ICON_SIZE_MEDIUM
            ),
            api_get_path(WEB_CODE_PATH).'work/category.php?action=list&'.api_get_cidreq()
        );

        if ($id) {
            $defaults = getCategory($id);

            $form->setDefaults($defaults);
        }
        // Form title
        $form->addElement('header', null, get_lang('EditCategory'));
        $form->addText('name', get_lang('Name'), true);

        $form->addElement('hidden', 'action', 'edit_category');
        $form->addElement('hidden', 'id', $id);
        $form->addElement('hidden', 'c_id', $idCourse);
        $form->addElement('hidden', 'id_session', api_get_session_id());

        $form->addButtonSave(get_lang('Update'));
        $tpl->assign('form', $form->returnForm());

        break;

    case 'delete':
        if ($id) {
            $result = deleteCategory($id);
            if($result){
                Display::addFlash(Display::return_message(get_lang('Deleted')));
                $url = api_get_self().'?action=list&'.api_get_cidreq();
                header('Location: '.$url);
                exit;
            }
        }
        break;
    case 'list':
        $actions = Display::url(
            Display::return_icon(
                'back.png',
                get_lang('BackToWorksList'),
                null,
                ICON_SIZE_MEDIUM
            ),
            api_get_path(WEB_CODE_PATH).'work/work.php?'.api_get_cidreq()
        );
        $actions .= Display::url(
            Display::return_icon(
                'add.png',
                get_lang('AddCategory'),
                null,
                ICON_SIZE_MEDIUM
            ),
            api_get_path(WEB_CODE_PATH).'work/category.php?action=add&'.api_get_cidreq()
        );
        $categories = listCategory($idCourse, $idSession);
        $tpl->assign('categories', $categories);
        break;
}
if (!empty($actions)) {
    $tpl->assign(
        'actions',
        Display::toolbarAction('toolbar', [$actions])
    );
}

if ($form->validate()) {
    $values = $form->getSubmitValues();
    if (!empty($values['id'])) {
        updateCategory($values);
        Display::addFlash(Display::return_message(get_lang('Updated')));
        $url = api_get_self().'?action=list&'.api_get_cidreq();
        header('Location: '.$url);
        exit;
    } else {
        createCategory($values);
        Display::addFlash(Display::return_message(get_lang('Added')));
        $url = api_get_self().'?action=list&'.api_get_cidreq();
        header('Location: '.$url);
        exit;
    }
}

$content = $tpl->fetch($tpl->get_template('work/category.tpl'));
$tpl->assign('content', $content);
$tpl->display_one_col_template();