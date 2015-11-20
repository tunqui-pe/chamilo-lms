<?php

/* For licensing terms, see /license.txt */
/**
 * List of achieved certificates by the current user
 * @author Angel Fernando Quiroz Campos <angel.quiroz@beeznest.com>
 * @package chamilo.gradebook
 */
$cidReset = true;

//require_once '../inc/global.inc.php';

if (api_is_anonymous()) {
    api_not_allowed(true);
}

$userId = api_get_user_id();

$courseList = GradebookUtils::getUserCertificatesInCourses($userId);
$sessionList = GradebookUtils::getUserCertificatesInSessions($userId);

if (empty($courseList) && empty($sessionList)) {
    Display::addFlash(
        Display::return_message(get_lang('YouNotYetAchievedCertificates'), 'warning')
    );
}

//$template = new Template(get_lang('MyCertificates'));

$template = \Chamilo\CoreBundle\Framework\Container::getTwig();

$template->addGlobal('course_list', $courseList);
$template->addGlobal('session_list', $sessionList);


if (api_get_setting('course.allow_public_certificates') == 'true') {
    $template->addGlobal(
        'actions',
        Display::toolbarButton(
            get_lang('SearchCertificates'),
            api_get_path(WEB_CODE_PATH) . "gradebook/search.php",
            'search',
            'info'
        )
    );
}

echo $template->render('@template_style/gradebook/my_certificates.html.twig');

