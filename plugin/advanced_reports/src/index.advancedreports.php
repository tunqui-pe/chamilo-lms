<?php
/* For license terms, see /license.txt */

/**
 * Index of the Buy Courses plugin courses list.
 *
 * @package chamilo.plugin.buycourses
 */
$plugin = AdvancedReportsPlugin::create();

$data = array();
if (!empty($_GET['report'])) {
    $data = $plugin->getReportData($_GET['report']);

    if (!empty($_GET['excel'])) {
        return Export::arrayToCsv($data, 'report');
    }
}

$tpl = new Template();

$sessionList = SessionManager::get_sessions_list();
$courseList = CourseManager::get_course_list();

$tpl->assign('sessionList', $sessionList);
$tpl->assign('courseList', $courseList);
$tpl->assign('data', $data);
$content = $tpl->fetch('advanced_reports/view/index.tpl');
$tpl->assign('content', $content);
$tpl->display_one_col_template();


