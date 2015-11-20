<?php
/* For licensing terms, see /license.txt */
/**
 * Show the skills report
 * @author Angel Fernando Quiroz Campos <angel.quiroz@beeznest.com>
 * @package chamilo.social.skill
 */
//require_once '../inc/global.inc.php';

$isStudent = api_is_student();
$isStudentBoss = api_is_student_boss();
$isDRH = api_is_drh();

if (!$isStudent && !$isStudentBoss && !$isDRH) {
    header('Location: ' . api_get_path(WEB_CODE_PATH) . 'social/skills_wheel.php');
    exit;
}

$userId = api_get_user_id();

$skillTable = Database::get_main_table(TABLE_MAIN_SKILL);
$skillRelUserTable = Database::get_main_table(TABLE_MAIN_SKILL_REL_USER);
$courseTable = Database::get_main_table(TABLE_MAIN_COURSE);

$tableRows = array();

//$tpl = new Template(get_lang('Skills'));
$tpl = \Chamilo\CoreBundle\Framework\Container::getTwig();
$tplPath = null;

$tpl->addGlobal(
    'allowSkillsTool',
    api_get_setting('skill.allow_skills_tool') == 'true'
);
$tpl->addGlobal(
    'allowDrhSkillsManagement',
    api_get_setting('gradebook.allow_hr_skills_management') == 'true'
);

if ($isStudent) {
    $sql = "SELECT s.name, sru.acquired_skill_at, c.title, c.directory
            FROM $skillTable s
            INNER JOIN $skillRelUserTable sru ON s.id = sru.skill_id
            INNER JOIN $courseTable c ON sru.course_id = c.id
            WHERE sru.user_id = $userId";

    $result = Database::query($sql);

    while ($resultData = Database::fetch_assoc($result)) {
        $tableRow = array(
            'skillName' => $resultData['name'],
            'achievedAt' => api_format_date($resultData['acquired_skill_at'], DATE_FORMAT_NUMBER),
            'courseImage' => Display::return_icon('course.png', null, null, ICON_SIZE_MEDIUM, null, true),
            'courseName' => $resultData['title']
        );

        $imageSysPath = sprintf("%s%s/course-pic.png", api_get_path(SYS_COURSE_PATH), $resultData['directory']);

        if (file_exists($imageSysPath)) {
            $thumbSysPath = sprintf("%s%s/course-pic32.png", api_get_path(SYS_COURSE_PATH), $resultData['directory']);
            $thumbWebPath = sprintf("%s%s/course-pic32.png", api_get_path(WEB_COURSE_PATH), $resultData['directory']);

            if (!file_exists($thumbSysPath)) {
                $courseImageThumb = new Image($imageSysPath);
                $courseImageThumb->resize(32, 32, 0);
                $courseImageThumb->send_image($thumbSysPath);
            }
            $tableRow['courseImage'] = $thumbWebPath;
        }
        $tableRows[] = $tableRow;
    }

    $tplPath = 'skill/student_report.html.twig';
} else if ($isStudentBoss) {
    $selectedStudent = isset($_REQUEST['student']) ? intval($_REQUEST['student']) : 0;
    $tableRows = array();
    $followedStudents = UserManager::getUsersFollowedByStudentBoss($userId);

    foreach ($followedStudents as &$student) {
        $student['completeName'] = api_get_person_name($student['firstname'], $student['lastname']);
    }

    if ($selectedStudent > 0) {
        $sql = "SELECT s.name, sru.acquired_skill_at, c.title, c.directory
                FROM $skillTable s
                INNER JOIN $skillRelUserTable sru ON s.id = sru.skill_id
                INNER JOIN $courseTable c ON sru.course_id = c.id
                WHERE sru.user_id = $selectedStudent
                ";

        $result = Database::query($sql);

        while ($resultData = Database::fetch_assoc($result)) {
            $tableRow = array(
                'completeName' => $followedStudents[$selectedStudent]['completeName'],
                'skillName' => $resultData['name'],
                'achievedAt' => api_format_date($resultData['acquired_skill_at'], DATE_FORMAT_NUMBER),
                'courseImage' => Display::return_icon('course.png', null, null, ICON_SIZE_MEDIUM, null, true),
                'courseName' => $resultData['title']
            );

            $imageSysPath = sprintf("%s%s/course-pic.png", api_get_path(SYS_COURSE_PATH), $resultData['directory']);

            if (file_exists($imageSysPath)) {
                $thumbSysPath = sprintf("%s%s/course-pic32.png", api_get_path(SYS_COURSE_PATH), $resultData['directory']);
                $thumbWebPath = sprintf("%s%s/course-pic32.png", api_get_path(WEB_COURSE_PATH), $resultData['directory']);

                if (!file_exists($thumbSysPath)) {
                    $courseImageThumb = new Image($imageSysPath);
                    $courseImageThumb->resize(32, 32, 0);
                    $courseImageThumb->send_image($thumbSysPath);
                }
                $tableRow['courseImage'] = $thumbWebPath;
            }
            $tableRows[] = $tableRow;
        }
    }

    $tplPath = 'skill/student_boss_report.html.twig';
    $tpl->addGlobal('followedStudents', $followedStudents);
    $tpl->addGlobal('selectedStudent', $selectedStudent);
} else if ($isDRH) {
    $selectedCourse = isset($_REQUEST['course']) ? intval($_REQUEST['course']) : null;
    $selectedSkill = isset($_REQUEST['skill']) ? intval($_REQUEST['skill']) : 0;

    $action = null;

    if (!empty($selectedCourse)) {
        $action = 'filterByCourse';
    } else if (!empty($selectedSkill)) {
        $action = 'filterBySkill';
    }

    $courses = CourseManager::get_courses_list();

    $tableRows = array();
    $reportTitle = null;

    $objSkill = new Skill();
    $skills = $objSkill->get_all();

    switch ($action) {
        case 'filterByCourse':
            $course = api_get_course_info_by_id($selectedCourse);
            $reportTitle = sprintf(get_lang('AchievedSkillInCourseX'), $course['name']);
            $tableRows = $objSkill->listAchievedByCourse($selectedCourse);
            break;
        case 'filterBySkill':
            $skill = $objSkill->get($selectedSkill);
            $reportTitle = sprintf(get_lang('StudentsWhoAchievedTheSkillX'), $skill['name']);
            $students = UserManager::getUsersFollowedByUser(
                $userId, STUDENT, false, false, false, null, null, null, null, null, null, DRH
            );

            $coursesFilter = array();
            foreach ($courses as $course) {
                $coursesFilter[] = $course['id'];
            }

            $tableRows = $objSkill->listUsersWhoAchieved($selectedSkill, $coursesFilter);
            break;
    }

    foreach ($tableRows as &$row) {
        $row['completeName'] = api_get_person_name($row['firstname'], $row['lastname']);
        $row['achievedAt'] = api_format_date($row['acquired_skill_at'], DATE_FORMAT_NUMBER);
        $row['courseImage'] = Display::return_icon('course.png', null, null, ICON_SIZE_MEDIUM, null, true);

        $imageSysPath = sprintf("%s%s/course-pic.png", api_get_path(SYS_COURSE_PATH), $row['c_directory']);

        if (file_exists($imageSysPath)) {
            $thumbSysPath = sprintf("%s%s/course-pic32.png", api_get_path(SYS_COURSE_PATH), $row['c_directory']);
            $thumbWebPath = sprintf("%s%s/course-pic32.png", api_get_path(WEB_COURSE_PATH), $row['c_directory']);

            if (!file_exists($thumbSysPath)) {
                $courseImageThumb = new Image($imageSysPath);
                $courseImageThumb->resize(32, 32, 0);
                $courseImageThumb->send_image($thumbSysPath);
            }

            $row['courseImage'] = $thumbWebPath;
        }
    }

    $tplPath = 'skill/drh_report.html.twig';

    $tpl->addGlobal('action', $action);
    $tpl->addGlobal('courses', $courses);
    $tpl->addGlobal('skills', $skills);
    $tpl->addGlobal('selectedCourse', $selectedCourse);
    $tpl->addGlobal('selectedSkill', $selectedSkill);
    $tpl->addGlobal('reportTitle', $reportTitle);
}

$tpl->addGlobal('rows', $tableRows);

echo $tpl->render("@template_style/".$tplPath);



