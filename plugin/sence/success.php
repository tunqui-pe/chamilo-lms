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

$userInfo = Session::read('_user');

if(!empty($_POST['IdSesionSence'])) {

    if ($userInfo['user_id'] == $_POST['IdSesionAlumno']) {

        $values = [
            'c_id' => api_get_course_int_id(),
            'user_id' => $userInfo['user_id'],
            'username' => $userInfo['username'],
            'code_sence' => $_POST['CodSence'],
            'code_course' => $_POST['CodigoCurso'],
            'id_session_sence' => $_POST['IdSesionSence'],
            'run_student' => $_POST['RunAlumno'],
            'date_login' => $_POST['FechaHora'],
            'time_zone' => $_POST['ZonaHoraria'],
            'training_line' => $_POST['LineaCapacitacion'],
            'glosa_error' => 0,
        ];

        $res = $plugin->registerLoginUserSence($values);
        $tpl->assign('status', true);
    }

} else {

    $res = $plugin->deteteLoginUserSence($courseInfo['real_id'], $userInfo['user_id']);
    $tpl->assign('status', false);
}

$tpl->assign('course', $courseInfo);
$content = $tpl->fetch('sence/view/sence_success.tpl');
$tpl->assign('content', $content);
$tpl->display_one_col_template();