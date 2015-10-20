<?php
/* For licensing terms, see /license.txt */
/**
 * Show information about the issued badge
 * @author Angel Fernando Quiroz Campos <angel.quiroz@beeznest.com>
 * @package chamilo.badge
 */
require_once '../inc/global.inc.php';

$userId = isset($_GET['user']) ? intval($_GET['user']) : 0;
$skillId = isset($_GET['skill']) ? intval($_GET['skill']) : 0;

if (!isset($_GET['user'], $_GET['skill'])) {
    header('Location: ' . api_get_path(WEB_PATH));
    exit;
}

$entityManager = Database::getManager();
$user = $entityManager->find('ChamiloUserBundle:User', $_GET['user']);
$skill = $entityManager->find('ChamiloCoreBundle:Skill', $_GET['skill']);

if (!$user || !$skill) {
    Display::addFlash(
        Display::return_message(get_lang('NoResults'), 'error')
    );

    header('Location: ' . api_get_path(WEB_PATH));
    exit;
}

$skillUserRepo = $entityManager->getRepository('ChamiloCoreBundle:SkillRelUser');
$userSkills = $skillUserRepo->findBy([
    'user' => $user->getId(),
    'skill' => $skill->getId()
]);

if (!$userSkills) {
    Display::addFlash(
        Display::return_message(
            sprintf(get_lang('TheUserXNotYetAchievedTheSkillX'), $user->getCompleteName(), $skill->getName()),
            'error'
        )
    );

    header('Location: ' . api_get_path(WEB_PATH));
    exit;
}

$userInfo = [
    'id' => $user->getId(),
    'complete_name' => $user->getCompleteName()
];

$skillInfo = [
    'id' => $skill->getId(),
    'name' => $skill->getName(),
    'short_code' => $skill->getShortCode(),
    'description' => $skill->getDescription(),
    'criteria' => $skill->getCriteria(),
    'badge_image' => $skill->getWebIconPath(),
    'courses' => []
];

$badgeAssertions = [];

foreach ($userSkills as $userSkill) {
    $sessionId = 0;
    $course = $userSkill->getCourse();
    $session = $userSkill->getSession();

    $courseName = '';

    if ($session) {
        $courseName = "[{$session->getName()}] ";
    }

    if ($course) {
        $courseName .= $course->getTitle();
    }

    $userSkillDate = api_get_local_time($userSkill->getAcquiredSkillAt());
    $skillInfo['courses'][] = [
        'name' => $courseName,
        'date_issued' => api_format_date($userSkillDate, DATE_TIME_FORMAT_LONG),
        'argumentation' => $userSkill->getArgumentation()
    ];

    $assertionUrl = api_get_path(WEB_CODE_PATH) . "badge/assertion.php?";
    $assertionUrl .= http_build_query(array(
        'user' => $user->getId(),
        'skill' => $skill->getId(),
        'course' => $course ? $course->getId() : 0,
        'session' => $session ? $session->getId() : 0
    ));

    $badgeAssertions[] = $assertionUrl;
}

$allowExport = api_get_user_id() == $user->getId();

if ($allowExport) {
    $backpack = 'https://backpack.openbadges.org/';

    $configBackpack = api_get_setting('openbadges_backpack');

    if (strcmp($backpack, $configBackpack) !== 0) {
        $backpack = $configBackpack;
    }

    $htmlHeadXtra[] = '<script src="' . $backpack . 'issuer.js"></script>';
}

$template = new Template('');
$template->assign('skill_info', $skillInfo);
$template->assign('user_info', $userInfo);
$template->assign('allow_export', $allowExport);

if ($allowExport) {
    $template->assign('assertions', $badgeAssertions);
}

$content = $template->fetch(
    $template->get_template('skill/issued.tpl')
);

$template->assign('header', get_lang('IssuedBadgeInformation'));
$template->assign('content', $content);
$template->display_one_col_template();
