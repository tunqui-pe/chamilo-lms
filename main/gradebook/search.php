<?php

/* For licensing terms, see /license.txt */
/**
 * Search user certificates if them are publics
 * @author Angel Fernando Quiroz Campos <angel.quiroz@beeznest.com>
 * @package chamilo.gradebook
 */
use \ChamiloSession as Session;

$cidReset = true;

//require_once '../inc/global.inc.php';

if (api_get_setting('course.allow_public_certificates') != 'true') {
    Display::return_message(get_lang('CertificatesNotPublic'), 'warning');
    api_not_allowed();
}

$userId = isset($_GET['id']) ? intval($_GET['id']) : 0;

$userList = $userInfo = $courseList = $sessionList = [];

$searchForm = new FormValidator('search_form', 'post', null, null);
$searchForm->addText('firstname', get_lang('FirstName'));
$searchForm->addText('lastname', get_lang('LastName'));
$searchForm->addButtonSearch();

if ($searchForm->validate()) {
    $firstname = $searchForm->getSubmitValue('firstname');
    $lastname = $searchForm->getSubmitValue('lastname');

    $userList = UserManager::getUserByName($firstname, $lastname);

    if (empty($userList)) {
        Display::addFlash(
            Display::return_message(get_lang('NoResults'), 'warning')
        );

        header('Location: '.api_get_self());
        exit;
    }
} elseif ($userId > 0) {
    $userInfo = api_get_user_info($userId);

    if (empty($userInfo)) {
        Display::addFlash(
            Display::return_message(get_lang('NoUser'), 'warning')
        );

        header('Location: '.api_get_self());
        exit;
    }

    $courseList = GradebookUtils::getUserCertificatesInCourses($userId, false);
    $sessionList = GradebookUtils::getUserCertificatesInSessions($userId, false);

    if (empty($courseList) && empty($sessionList)) {
        Display::addFlash(
            Display::return_message(
                sprintf(get_lang('TheUserXNotYetAchievedCertificates'), $userInfo['complete_name']),
                'warning'
            )
        );

        header('Location: '.api_get_self());
        exit;
    }
}

//$template = new Template(get_lang('SearchCertificates'));
$template = \Chamilo\CoreBundle\Framework\Container::getTwig();

$template->addGlobal('search_form', $searchForm->returnForm());
$template->addGlobal('user_list', $userList);
$template->addGlobal('user_info', $userInfo);
$template->addGlobal('course_list', $courseList);
$template->addGlobal('session_list', $sessionList);
$template->addGlobal('header', get_lang('SearchCertificates'));

echo $template->render('@template_style/gradebook/search.html.twig');

