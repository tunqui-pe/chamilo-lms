<?php
/* For licensing terms, see /license.txt */

use Chamilo\CoreBundle\Framework\Container;

/*
 * Report from students for learning path
 */
//require_once '../inc/global.inc.php';

$isAllowedToEdit = api_is_allowed_to_edit(null, true);

if (!$isAllowedToEdit) {
    api_not_allowed(true);
}

$lpTable = Database::get_course_table(TABLE_LP_MAIN);

$lpId = isset($_GET['lp_id']) ? intval($_GET['lp_id']) : false;
if (empty($lpId)) {
    api_not_allowed(true);
}
$sessionId = api_get_session_id();
$courseId = api_get_course_int_id();
$courseCode = api_get_course_id();

if (empty($sessionId)) {
    $status = STUDENT;
    $users = CourseManager::get_user_list_from_course_code(
        $courseCode,
        0,
        null,
        null,
        $status
    );
} else {
    $status = 0; // student
    $users = CourseManager::get_user_list_from_course_code(
        $courseCode,
        $sessionId,
        null,
        null,
        $status
    );
}

$lpInfo = Database::select(
    '*',
    $lpTable,
    array(
        'where' => array(
            'c_id = ? AND ' => $courseId,
            'id = ?' => $lpId
        )
    ),
    'first'
);

$userList = [];

if (!empty($users)) {
    foreach ($users as $user) {
        $userInfo = api_get_user_info($user['user_id']);
        $lpTime = Tracking::get_time_spent_in_lp(
            $user['user_id'],
            $courseCode,
            array($lpId),
            $sessionId
        );

        $lpScore = Tracking::get_avg_student_score(
            $user['user_id'],
            $courseCode,
            array($lpId),
            $sessionId
        );

            $lpProgress = Tracking::get_avg_student_progress(
            $user['user_id'],
            $courseCode,
            array($lpId),
            $sessionId
        );

        $lpLastConnection = Tracking::get_last_connection_time_in_lp(
            $user['user_id'],
            $courseCode,
            array($lpId),
            $sessionId
        );

        $lpLastConnection = empty($lpLastConnection) ? '-' : api_convert_and_format_date(
            $lpLastConnection,
            DATE_TIME_FORMAT_LONG
        );

        $userList[] = [
            'id' => $user['user_id'],
            'first_name' => $userInfo['firstname'],
            'last_name' => $userInfo['lastname'],
            'lp_time' => api_time_to_hms($lpTime),
            'lp_score' => is_numeric($lpScore) ? "$lpScore%" : $lpScore,
            'lp_progress' => "$lpProgress%",
            'lp_last_connection' => $lpLastConnection
        ];
    }
}

// View
$interbreadcrumb[] = [
    'url' => api_get_path(WEB_CODE_PATH) . 'newscorm/lp_controller.php',
    'name' => get_lang('LearningPaths')
];

$actions = Display::url(
    Display::return_icon(
        'back.png',
        get_lang('Back'),
        array(),
        ICON_SIZE_MEDIUM
    ),
    api_get_path(WEB_CODE_PATH) . 'newscorm/lp_controller.php?' . api_get_cidreq()
);

$template = \Chamilo\CoreBundle\Framework\Container::getTwig();
$template->addGlobal('user_list', $userList);
$template->addGlobal('session_id', api_get_session_id());
$template->addGlobal('course_code', api_get_course_id());
$template->addGlobal('lp_id', $lpId);
$template->addGlobal('header', $lpInfo['name']);
$template->addGlobal('actions', $actions);

echo $template->render('@template_style/learnpath/report.html.twig');
