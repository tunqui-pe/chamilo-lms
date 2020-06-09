<?php
/**
 * This script initiates a video conference session, calling the SENCE Conector API.
 */

use ChamiloSession as Session;
require_once __DIR__.'/../../vendor/autoload.php';

require_once __DIR__.'/config.php';

$htmlHeadXtra[] = '<link rel="stylesheet" type="text/css" href="'.api_get_path(
        WEB_PLUGIN_PATH
    ).'sence/resources/css/style.css"/>';

$plugin = SencePlugin::create();

$tool_name = $plugin->get_lang('CorrectTiming');
$tpl = new Template($tool_name);

$courseInfo = api_get_course_info();
$message = null;
$userInfo = Session::read('_user');


if(!empty($_POST['IdSesionSence'])) {

    if ($userInfo['user_id'] == $_POST['IdSesionAlumno']) {

        $values = [
            'c_id' => api_get_course_int_id(),
            'id_session' => api_get_session_id(),
            'user_id' => $userInfo['user_id'],
            'username' => $userInfo['username'],
            'firstname' => $userInfo['firstname'],
            'lastname' => $userInfo['lastname'],
            'code_sence' => $_POST['CodSence'],
            'code_course' => $_POST['CodigoCurso'],
            'id_session_sence' => $_POST['IdSesionSence'],
            'run_student' => $_POST['RunAlumno'],
            'date_login' => $_POST['FechaHora'],
            'time_zone' => $_POST['ZonaHoraria'],
            'training_line' => $_POST['LineaCapacitacion'],
            'glosa_error' => 0,
            'type_login' => 1
        ];
        $senceInfoUser = $plugin->getLoginUserSenceInfo($courseInfo['real_id'], $userInfo['user_id']);
        if(!$senceInfoUser){
            $res = $plugin->registerLoginUserSence($values);
            $resLogs = $plugin->registerLogs($values);
        } else {
            Display::addFlash(
                Display::return_message($plugin->get_lang('SessionAlreadyRegistered'))
            );
        }

        $urlCourse = api_get_course_url($courseInfo['code']);

        $tpl->assign('url_course', $urlCourse);
        $tpl->assign('check', true);
    }

} else {

    $idSession = $plugin->getIdSessionSenceUser($userInfo['user_id']);

    $values = [
        'c_id' => api_get_course_int_id(),
        'id_session' => api_get_session_id(),
        'user_id' => $userInfo['user_id'],
        'username' => $userInfo['username'],
        'firstname' => $userInfo['firstname'],
        'lastname' => $userInfo['lastname'],
        'code_sence' => $_POST['CodSence'],
        'code_course' => $_POST['CodigoCurso'],
        'id_session_sence' => $idSession,
        'run_student' => $_POST['RunAlumno'],
        'date_login' => $_POST['FechaHora'],
        'time_zone' => $_POST['ZonaHoraria'],
        'training_line' => $_POST['LineaCapacitacion'],
        'glosa_error' => 0,
        'type_login' => 2
    ];

    $resLogs = $plugin->registerLogs($values);

    $res = $plugin->deteteLoginUserSence($courseInfo['real_id'], $userInfo['user_id']);
    $urlListCourses = api_get_path(WEB_PATH).'user_portal.php';
    $tpl->assign('url_list_courses', $urlListCourses);
    $tpl->assign('check', false);
}

$tpl->assign('course', $courseInfo);
$content = $tpl->fetch('sence/view/sence_success.tpl');
$tpl->assign('content', $content);
$tpl->display_one_col_template();