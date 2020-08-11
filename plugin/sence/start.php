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

$message = null;

$courseInfo = api_get_course_info();
$idCourse = $courseInfo['real_id'];

$action = isset($_GET['action']) ? $_GET['action'] : null;
$enable = $plugin->get('sence_enabled') == 'true';
$environment = $plugin->get('environment') == 'true';

$isTeacher = api_is_teacher();
$isStudent = api_is_student();
$isAdmin = api_is_platform_admin();
$idStudent = api_get_user_id();

if ($enable) {
    $senceInfo = $plugin->getSenceInfo($courseInfo['real_id']);
    $multiAction = $senceInfo['action_id'] == '1';
    $tpl->assign('sence', $senceInfo);
    //var_dump($senceInfoUser = $plugin->getLoginUserSenceInfo($idCourse, $idStudent));
    $urlPageError = api_get_path(WEB_PLUGIN_PATH).'sence/error.php?'.api_get_cidreq();
    $urlPageSuccess = api_get_path(WEB_PLUGIN_PATH).'sence/success.php?'.api_get_cidreq();

    if ($isAdmin || $isTeacher) {

        $urlAdd = api_get_path(WEB_PLUGIN_PATH).'sence/associate.php?action=add&'.api_get_cidreq();
        if ($senceInfo) {
            $urlEdit = api_get_path(WEB_PLUGIN_PATH).'sence/associate.php?action=edit&'.api_get_cidreq();
            $urlDelete = api_get_path(WEB_PLUGIN_PATH).'sence/associate.php?action=delete&id_sence='.$senceInfo['id'].'&'.api_get_cidreq();
            $urlList = api_get_path(WEB_PLUGIN_PATH).'sence/list.php?action=list&'.api_get_cidreq();

            $tpl->assign('url_edit_sence', $urlEdit);
            $tpl->assign('url_list', $urlList);
            $tpl->assign('url_delete_sence', $urlDelete);
        } else {
            $tpl->assign('url_add_sence', $urlAdd);
        }

    } else {

        $urlLogin = $plugin->getURLSenceLogin($environment);
        $senceInfoUser = $plugin->getLoginUserSenceInfo($idCourse, $idStudent);

        if ($senceInfoUser) {
            //logout form

            $form = new FormValidator(
                'login_sence',
                'post',
                $urlLogin['logout']
            );

            $form->addHidden('RutOtec', $plugin->get('rut_otec'));
            $form->addHidden('Token', $plugin->get('token_otec'));
            $form->addHidden('CodSence', $senceInfoUser['code_sence']);
            $form->addHidden('CodigoCurso', $senceInfoUser['code_course']);
            $form->addHidden('LineaCapacitacion', $senceInfoUser['training_line']);
            $form->addHidden('RunAlumno', $senceInfoUser['run_student']);
            $form->addHidden('IdSesionAlumno', $senceInfoUser['user_id']);
            $form->addHidden('IdSesionSence', $senceInfoUser['id_session_sence']);
            $form->addHidden('UrlRetoma', $urlPageSuccess);
            $form->addHidden('UrlError', $urlPageError);
            $form->addButtonCancel(
                $plugin->get_lang('ButtonLogout')
            );

            $tpl->assign('form_login', $form->returnForm());
            $tpl->assign('check', true);
            $tpl->assign('sence_user', $senceInfoUser);

        } else {

            //login form
            $idGroupUser = intval($plugin->getUserInGroup($idStudent));
            $idGroupCourse = intval($plugin->getSenceGroupUser($idCourse));

            if (($idGroupUser != $idGroupCourse) || $idGroupUser == 0) {
                if (isset($senceInfo['code_sence'])) {
                    $form = new FormValidator(
                        'login_sence',
                        'post',
                        $urlLogin['login']
                    );

                    $form->addHidden('RutOtec', $plugin->get('rut_otec'));
                    $form->addHidden('Token', $plugin->get('token_otec'));

                    $form->addText(
                        'RunAlumno',
                        [
                            $plugin->get_lang('RunStudentSence'),
                            $plugin->get_lang('RunStudentSenceHelp'),
                        ],
                        true,
                        [
                            'title' => $plugin->get_lang('RunStudentSenceHelp'),
                            'required' => 'required',
                        ]
                    );
                    if($multiAction){
                        $form->addText(
                            'CodigoCurso',
                            [
                                $plugin->get_lang('CodeCourse'),
                                $plugin->get_lang('CodeCourseHelp'),
                            ],
                            true,
                            [
                                'title' => $plugin->get_lang('CodeSenceHelp'),
                                'required' => 'required',
                            ]
                        );
                    } else {
                        $form->addHidden('CodigoCurso', $senceInfo['code_course']);
                    }
                    $form->addHidden('ActionId', $senceInfo['action_id']);
                    $form->addHidden('CodSence', $senceInfo['code_sence']);
                    $form->addHidden('LineaCapacitacion', $senceInfo['training_line']);
                    $form->addHidden('IdSesionAlumno', $idStudent);
                    $form->addHidden('UrlRetoma', $urlPageSuccess);
                    $form->addHidden('UrlError', $urlPageError);

                    $form->addButtonSave(
                        $plugin->get_lang('ButtonLogin')
                    );
                    $tpl->assign('form_login', $form->returnForm());
                    $tpl->assign('check', false);
                }
            } else {
                $tpl->assign('scholar', true);
            }
        }
        $tpl->assign('company_name', $plugin->get('company_name'));
        $tpl->assign('rut_otec', $plugin->get('rut_otec'));
    }
}


$tpl->assign('is_teacher', $isTeacher);
$tpl->assign('is_student', $isStudent);
$tpl->assign('is_admin', $isAdmin);

$tpl->assign('course', $courseInfo);
$tpl->assign('message', $message);
$content = $tpl->fetch('sence/view/sence_start.tpl');
$tpl->assign('content', $content);
$tpl->display_one_col_template();
