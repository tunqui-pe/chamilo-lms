<?php
/* For licensing terms, see /license.txt */
/**
 * Show information about the issued badge
 * @author Angel Fernando Quiroz Campos <angel.quiroz@beeznest.com>
 * @package chamilo.badge
 */
require_once '../inc/global.inc.php';

if (!isset($_GET['user'], $_GET['issue'])) {
    header('Location: ' . api_get_path(WEB_PATH));
    exit;
}

$entityManager = Database::getManager();
$skillIssue = $entityManager->find('ChamiloCoreBundle:SkillRelUser', $_GET['issue']);

if (!$skillIssue) {
    Display::addFlash(
        Display::return_message(get_lang('NoResults'), 'error')
    );

    header('Location: ' . api_get_path(WEB_PATH));
    exit;
}

if ($skillIssue->getUser()->getId() !== intval($_GET['user'])) {
    Display::addFlash(
        Display::return_message(get_lang('NoResults'), 'error')
    );

    header('Location: ' . api_get_path(WEB_PATH));
    exit;
}

$skillIssueDate = api_get_local_time($skillIssue->getAcquiredSkillAt());

$skillIssueInfo = [
    'id' => $skillIssue->getId(),
    'datetime' => api_format_date($skillIssueDate, DATE_TIME_FORMAT_SHORT),
    'argumentation' => $skillIssue->getArgumentation(),
    'source_name' => $skillIssue->getSourceName(),
    'user_complete_name' => $skillIssue->getUser()->getCompleteName(),
    'skill_badge_image' => $skillIssue->getSkill()->getWebIconPath(),
    'skill_name' => $skillIssue->getSkill()->getName(),
    'skill_short_code' => $skillIssue->getSkill()->getShortCode(),
    'skill_description' => $skillIssue->getSkill()->getDescription(),
    'skill_criteria' => $skillIssue->getSkill()->getCriteria(),
    'badge_asserion' => [$skillIssue->getAssertionUrl()]
];

$allowExport = api_get_user_id() === $skillIssue->getUser()->getId();

if ($allowExport) {
    $backpack = 'https://backpack.openbadges.org/';

    $configBackpack = api_get_setting('openbadges_backpack');

    if (strcmp($backpack, $configBackpack) !== 0) {
        $backpack = $configBackpack;
    }

    $htmlHeadXtra[] = '<script src="' . $backpack . 'issuer.js"></script>';
}

$template = new Template('');
$template->assign('issue_info', $skillIssueInfo);
$template->assign('allow_export', $allowExport);

$content = $template->fetch(
    $template->get_template('skill/issued.tpl')
);

$template->assign('header', get_lang('IssuedBadgeInformation'));
$template->assign('content', $content);
$template->display_one_col_template();
