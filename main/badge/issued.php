<?php
/* For licensing terms, see /license.txt */
/**
 * Show information about the issued badge
 * @author Angel Fernando Quiroz Campos <angel.quiroz@beeznest.com>
 * @package chamilo.badge
 */
require_once '../inc/global.inc.php';

if (!isset($_REQUEST['user'], $_REQUEST['issue'])) {
    header('Location: ' . api_get_path(WEB_PATH));
    exit;
}

$entityManager = Database::getManager();
$skillIssue = $entityManager->find('ChamiloCoreBundle:SkillRelUser', $_REQUEST['issue']);

if (!$skillIssue) {
    Display::addFlash(
        Display::return_message(get_lang('NoResults'), 'error')
    );

    header('Location: ' . api_get_path(WEB_PATH));
    exit;
}

if ($skillIssue->getUser()->getId() !== intval($_REQUEST['user'])) {
    Display::addFlash(
        Display::return_message(get_lang('NoResults'), 'error')
    );

    header('Location: ' . api_get_path(WEB_PATH));
    exit;
}

$currentUserId = api_get_user_id();
$skillIssueDate = api_get_local_time($skillIssue->getAcquiredSkillAt());

$skillIssueInfo = [
    'id' => $skillIssue->getId(),
    'datetime' => api_format_date($skillIssueDate, DATE_TIME_FORMAT_SHORT),
    'argumentation' => $skillIssue->getArgumentation(),
    'source_name' => $skillIssue->getSourceName(),
    'user_id' => $skillIssue->getUser()->getId(),
    'user_complete_name' => $skillIssue->getUser()->getCompleteName(),
    'skill_badge_image' => $skillIssue->getSkill()->getWebIconPath(),
    'skill_name' => $skillIssue->getSkill()->getName(),
    'skill_short_code' => $skillIssue->getSkill()->getShortCode(),
    'skill_description' => $skillIssue->getSkill()->getDescription(),
    'skill_criteria' => $skillIssue->getSkill()->getCriteria(),
    'badge_asserion' => [$skillIssue->getAssertionUrl()],
    'comments' => []
];

$skillIssueComments = $skillIssue->getComments(true);

foreach ($skillIssueComments as $comment) {
    $commentDate = api_get_local_time($comment->getFeedbackDateTime());

    $skillIssueInfo['comments'][] = [
        'text' => $comment->getFeedbackText(),
        'value' => $comment->getFeedbackValue(),
        'giver_complete_name' => $comment->getFeedbackGiver()->getCompleteName(),
        'datetime' => api_format_date($commentDate, DATE_TIME_FORMAT_SHORT)
    ];
}

$form = new FormValidator('comment');
$form->addTextarea('comment', get_lang('NewComment'), ['rows' => 4]);
$form->applyFilter('comment', 'trim');
$form->addRule('comment', get_lang('ThisFieldIsRequired'), 'required');
$form->addSelect(
    'value',
    [get_lang('Value'), get_lang('RateTheSkillInPractice')],
    ['-', 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
);
$form->addHidden('user', $skillIssue->getUser()->getId());
$form->addHidden('issue', $skillIssue->getId());
$form->addButtonSend(get_lang('Send'));

if ($form->validate()) {
    $currentUser = $entityManager->find('ChamiloUserBundle:User', $currentUserId);
    $values = $form->exportValues();

    $skillUserComment = new Chamilo\CoreBundle\Entity\SkillRelUserComment();
    $skillUserComment
        ->setFeedbackDateTime(new DateTime)
        ->setFeedbackGiver($currentUser)
        ->setFeedbackText($values['comment'])
        ->setFeedbackValue($values['value'])
        ->setSkillRelUser($skillIssue);

    $entityManager->persist($skillUserComment);
    $entityManager->flush();

    header("Location: " . $skillIssue->getIssueUrl());
    exit;
}

$allowExport = $currentUserId === $skillIssue->getUser()->getId();

if ($allowExport) {
    $backpack = 'https://backpack.openbadges.org/';

    $configBackpack = api_get_setting('openbadges_backpack');

    if (strcmp($backpack, $configBackpack) !== 0) {
        $backpack = $configBackpack;
    }

    $htmlHeadXtra[] = '<script src="' . $backpack . 'issuer.js"></script>';
}

$template = new Template(get_lang('IssuedBadgeInformation'));
$template->assign('issue_info', $skillIssueInfo);
$template->assign('allow_export', $allowExport);
$template->assign('comment_form', $form->returnForm());

$content = $template->fetch(
    $template->get_template('skill/issued.tpl')
);

$template->assign('header', get_lang('IssuedBadgeInformation'));
$template->assign('content', $content);
$template->display_one_col_template();
