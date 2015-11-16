<?php
/* For licensing terms, see /license.txt */
/**
 * Session about page
 * Show information about a session and its courses
 * @author Angel Fernando Quiroz Campos <angel.quiroz@beeznest.com>
 * @package chamilo.session
 */
use Chamilo\CoreBundle\Entity\ExtraField;
use Chamilo\CourseBundle\Entity\CCourseDescription;
use \Chamilo\CoreBundle\Entity\SequenceResource;

$cidReset = true;

//require_once '../inc/global.inc.php';

$sessionId = isset($_GET['session_id']) ? intval($_GET['session_id']) : 0;

$entityManager = Database::getManager();

$session = $entityManager->find('ChamiloCoreBundle:Session', $sessionId);

if (!$session) {
    api_not_allowed(true);
}

$sessionCourses = $entityManager->getRepository('ChamiloCoreBundle:Session')
    ->getCoursesOrderedByPosition($session);

$courses = [];

$entityManager = Database::getManager();
$fieldsRepo = $entityManager->getRepository('ChamiloCoreBundle:ExtraField');
$fieldTagsRepo = $entityManager->getRepository('ChamiloCoreBundle:ExtraFieldRelTag');
$userRepo = $entityManager->getRepository('ChamiloUserBundle:User');
$sequenceResourceRepo = $entityManager->getRepository(
    'ChamiloCoreBundle:SequenceResource'
);

$tagField = $fieldsRepo->findOneBy([
    'extraFieldType' => ExtraField::COURSE_FIELD_TYPE,
    'variable' => 'tags'
]);

$courseValues = new ExtraFieldValue('course');
$userValues = new ExtraFieldValue('user');
$sessionValues = new ExtraFieldValue('session');

foreach ($sessionCourses as $sessionCourse) {
    $courseTags = [];

    if (!is_null($tagField)) {
        $courseTags = $fieldTagsRepo->getTags($tagField, $sessionCourse->getId());
    }

    $courseCoaches = $userRepo->getCoachesForSessionCourse($session, $sessionCourse);
    $coachesData = [];

    foreach ($courseCoaches as $courseCoach) {
        $coachData = [
            'complete_name' => $courseCoach->getCompleteName(),
            'image' => UserManager::getUserPicture($courseCoach->getId(), USER_IMAGE_SIZE_ORIGINAL),
            'extra_fields' => $userValues->getAllValuesForAnItem($courseCoach->getId(), true)
        ];

        $coachesData[] = $coachData;
    }

    $courseDescriptionTools = $entityManager->getRepository('ChamiloCourseBundle:CCourseDescription')
        ->findBy(
            [
                'cId' => $sessionCourse->getId(),
                'sessionId' => 0
            ],
            [
                'id' => 'DESC',
                'descriptionType' => 'ASC'
            ]
        );

    $courseDescription = $courseObjectives = $courseTopics = null;

    foreach ($courseDescriptionTools as $descriptionTool) {
        switch ($descriptionTool->getDescriptionType()) {
            case CCourseDescription::TYPE_DESCRIPTION:
                $courseDescription = $descriptionTool;
                break;
            case CCourseDescription::TYPE_OBJECTIVES:
                $courseObjectives = $descriptionTool;
                break;
            case CCourseDescription::TYPE_TOPICS:
                $courseTopics = $descriptionTool;
                break;
        }
    }

    $courses[] = [
        'course' => $sessionCourse,
        'description' => $courseDescription,
        'tags' => $courseTags,
        'objectives' => $courseObjectives,
        'topics' => $courseTopics,
        'coaches' => $coachesData,
        'extra_fields' => $courseValues->getAllValuesForAnItem($sessionCourse->getId())
    ];
}

$sessionDates = SessionManager::parseSessionDates([
    'display_start_date' => $session->getDisplayStartDate(),
    'display_end_date' => $session->getDisplayEndDate(),
    'access_start_date' => $session->getAccessStartDate(),
    'access_end_date' => $session->getAccessEndDate(),
    'coach_access_start_date' => $session->getCoachAccessStartDate(),
    'coach_access_end_date' => $session->getCoachAccessEndDate()
]);

$sessionRequirements = $sequenceResourceRepo->getRequirements(
    $session->getId(),
    SequenceResource::SESSION_TYPE
);

$hasRequirements = false;

foreach ($sessionRequirements as $sequence) {
    if (!empty($sequence['requirements'])) {
        $hasRequirements = true;
        break;
    }
}

$courseController = new CoursesController();

/* View */
//$template = new Template($session->getName(), true, true, false, true, false);
$template = \Chamilo\CoreBundle\Framework\Container::getTwig();

$template->addGlobal(
    'show_tutor',
    (api_get_setting('session.show_session_coach') === 'true' ? true : false)
);
$template->addGlobal(
    'pageUrl',
    api_get_path(WEB_PATH)."session/{$session->getId()}/about/"
);
$template->addGlobal('session', $session);
$template->addGlobal('session_date', $sessionDates);
$template->addGlobal(
    'is_subscribed',
    SessionManager::isUserSubscribedAsStudent(
        $session->getId(),
        api_get_user_id()
    )
);
$template->addGlobal(
    'subscribe_button',
    $courseController->getRegisteredInSessionButton(
        $session->getId(),
        $session->getName(),
        $hasRequirements
    )
);

$template->addGlobal('courses', $courses);
$template->addGlobal('essence', \Essence\Essence::instance());
$template->addGlobal(
    'session_extra_fields',
    $sessionValues->getAllValuesForAnItem($session->getId(), true)
);
$template->addGlobal('has_requirements', $hasRequirements);
$template->addGlobal('sequences', $sessionRequirements);

//$templateFolder = api_get_configuration_value('default_template');

echo $template->render('@template_style/session/about.html.twig');



//$template->assign('header', $session->getName());


