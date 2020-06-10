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

$courseInfo = api_get_course_info();
$message = null;
$userInfo = Session::read('_user');

$tool_name = $plugin->get_lang('SynchronizationFailed');
$tpl = new Template($tool_name);

if(empty($_POST['IdSesionSence'])) {

    $values = [
        'c_id' => api_get_course_int_id(),
        'id_session' => api_get_session_id(),
        'user_id' => $userInfo['user_id'],
        'username' => $userInfo['username'],
        'people_name' => $userInfo['complete_name'],
        'code_sence' => $_POST['CodSence'],
        'code_course' => $_POST['CodigoCurso'],
        'id_session_sence' => null,
        'run_student' => $_POST['RunAlumno'],
        'date_login' => $_POST['FechaHora'],
        'time_zone' => $_POST['ZonaHoraria'],
        'training_line' => $_POST['LineaCapacitacion'],
        'glosa_error' => $_POST['GlosaError'],
        'type_login' => 3
    ];

    $messageError = $plugin->getErrorLoginMessage(intval($_POST['GlosaError']));
    $urlListCourses = api_get_path(WEB_PATH).'user_portal.php';
    $tpl->assign('url_list_courses', $urlListCourses);
    $tpl->assign('error_code', $_POST['GlosaError']);
    $tpl->assign('message_error', $messageError);
    $resLogs = $plugin->registerLogs($values);
}

$content = $tpl->fetch('sence/view/sence_error.tpl');
$tpl->assign('content', $content);
$tpl->display_one_col_template();