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
$actionLinks = '';

if ($enable) {
    if (api_is_platform_admin()) {

        switch ($action) {
            case 'add':

                $actionLinks .= Display::url(
                    Display::return_icon('back.png', get_lang('Back'), [], ICON_SIZE_MEDIUM),
                    api_get_path(WEB_PLUGIN_PATH).'sence/start.php?'.api_get_cidreq()
                );

                //Add Code Sence
                $form = new FormValidator(
                    'add_code_sence',
                    'post',
                    api_get_self().'?action='.Security::remove_XSS($_GET['action']).'&'.api_get_cidreq()
                    );
                $form->addHeader($plugin->get_lang('SynchronizeYourCourse'));

                $list = $plugin->getListGroupCourse();

                $form->addText(
                    'code_sence',
                    [
                        $plugin->get_lang('CodeSence'),
                        $plugin->get_lang('CodeSenceHelp')
                    ],
                    true,
                    [
                        'title'=>$plugin->get_lang('CodeSenceHelp')
                    ]
                );

                $form->addText(
                    'code_course',
                    [
                        $plugin->get_lang('CodeCourse'),
                        $plugin->get_lang('CodeCourseHelp')
                    ],
                    false,
                    [
                        'title'=>$plugin->get_lang('CodeSenceHelp')
                    ]
                );

                $form->addCheckBox(
                    'action_id',
                    $plugin->get_lang('ActionIdCheckMulti'),
                    $plugin->get_lang('ActionIdCheck')
                );

                $options = $plugin->getTrainingLines();

                $form->addSelect(
                    'training_line',
                    $plugin->get_lang('TrainingLine'),
                    $options
                );


                $form->addSelect(
                    'id_group',
                    [
                        $plugin->get_lang('ScholarshipGroup'),
                        $plugin->get_lang('ScholarshipGroupHelp')
                    ],
                    $list,
                    [
                        'title'=>$plugin->get_lang('ScholarshipGroupOptional')
                    ]
                );

                $form->addButtonSave($plugin->get_lang('SaveCodeSence'));
                $dataSence['training_line'] = 3;
                $dataSence['id_group'] = -1;
                $form->setDefaults($dataSence);

                if ($form->validate()) {

                    $values = $form->exportValues();
                    $res = $plugin->registerCodeSenceCourse($values);

                    if ($res) {
                        $url = api_get_path(WEB_PLUGIN_PATH).'sence/start.php';
                        header('Location: '.$url);
                    }

                }
                $tpl->assign('form_sence', $form->returnForm());

                break;
            case 'edit':

                $actionLinks .= Display::url(
                    Display::return_icon('back.png', get_lang('Back'), [], ICON_SIZE_MEDIUM),
                    api_get_path(WEB_PLUGIN_PATH).'sence/start.php?'.api_get_cidreq()
                );

                $idCourse = api_get_course_int_id();
                $dataSence = $plugin->getSenceInfo($idCourse);

                //Edit Code Sence
                $form = new FormValidator(
                    'edit_code_sence',
                    'post',
                    api_get_self().'?action='.Security::remove_XSS($_GET['action']).'&'.api_get_cidreq()
                );
                $form->addHeader($plugin->get_lang('SynchronizeYourCourse'));

                $list = $plugin->getListGroupCourse();

                $form->addText(
                    'code_sence',
                    [
                        $plugin->get_lang('CodeSence'),
                        $plugin->get_lang('CodeSenceHelp')
                    ],
                    true,
                    [
                        'title'=>$plugin->get_lang('CodeSenceHelp')
                    ]
                );

                $form->addText(
                    'code_course',
                    [
                        $plugin->get_lang('CodeCourse'),
                        $plugin->get_lang('CodeCourseHelp')
                    ],
                    false,
                    [
                        'title'=>$plugin->get_lang('CodeSenceHelp')
                    ]
                );

                $form->addCheckBox(
                    'action_id',
                    $plugin->get_lang('ActionIdCheckMulti'),
                    $plugin->get_lang('ActionIdCheck')
                );

                $options = $plugin->getTrainingLines();

                $form->addSelect(
                    'training_line',
                    $plugin->get_lang('TrainingLine'),
                    $options
                );

                $form->addSelect(
                    'id_group',
                    [
                        $plugin->get_lang('ScholarshipGroup'),
                        $plugin->get_lang('ScholarshipGroupHelp')
                    ],
                    $list,
                    [
                        'title'=>$plugin->get_lang('ScholarshipGroupOptional')
                    ]
                );

                $form->addHidden('id', $dataSence['id']);
                //$form->addHidden('action_id', $dataSence['action_id']);
                $form->addButtonSave($plugin->get_lang('SaveCodeSence'));

                try {
                    $form->setDefaults($dataSence);
                    $dataSence = [];
                } catch (Exception $e) {
                    echo $e;
                }

                if ($form->validate()) {
                    $values = $form->exportValues();
                    $res = $plugin->updateCodeSenceCourse($values);
                    if ($res) {
                        $url = api_get_path(WEB_PLUGIN_PATH).'sence/start.php?'.api_get_cidreq();
                        header('Location: '.$url);
                    }
                }
                $tpl->assign('form_sence', $form->returnForm());

                break;

            case 'delete':

                $idRoom = isset($_GET['id_sence']) ? $_GET['id_sence'] : null;
                $res = $plugin->deleteSenceCourse($idRoom);
                if ($res) {
                    $url = api_get_path(WEB_PLUGIN_PATH).'sence/start.php?'.api_get_cidreq();
                    header('Location: '.$url);
                }

                break;

            default :

                break;

        }

    }
}


if ($isAdmin || $isTeacher) {

    $tpl->assign(
        'actions',
        Display::toolbarAction('toolbar', [$actionLinks])
    );
}

$tpl->assign('course', $courseInfo);
$tpl->assign('message', $message);
$content = $tpl->fetch('sence/view/sence_associate.tpl');
$tpl->assign('content', $content);
$tpl->display_one_col_template();
