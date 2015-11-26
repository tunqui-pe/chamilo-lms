<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\CoreBundle\Security\Authorization\Voter;

use Chamilo\CoreBundle\Entity\Course;
use Chamilo\CoreBundle\Entity\Manager\CourseManager;
use Chamilo\CoreBundle\Entity\Session;
use Chamilo\UserBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\AbstractVoter;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class SessionVoter
 * @package Chamilo\CoreBundle\Security\Authorization\Voter
 */
class SessionVoter extends AbstractVoter
{
    const VIEW = 'VIEW';
    const EDIT = 'EDIT';
    const DELETE = 'DELETE';

    private $entityManager;
    private $courseManager;

    /**
     * @param EntityManager $entityManager
     * @param CourseManager $courseManager
     * @param ContainerInterface $container
     */
    public function __construct(
        EntityManager $entityManager,
        CourseManager $courseManager,
        ContainerInterface $container
    )
    {
        $this->entityManager = $entityManager;
        $this->courseManager = $courseManager;
        $this->container = $container;
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * @return CourseManager
     */
    public function getCourseManager()
    {
        return $this->courseManager;
    }

    /**
     * {@inheritdoc}
     */
    protected function getSupportedAttributes()
    {
        return array(self::VIEW, self::EDIT, self::DELETE);
    }

    /**
     * {@inheritdoc}
     */
    protected function getSupportedClasses()
    {
        return array('Chamilo\CoreBundle\Entity\Session');
    }

    /**
     * Check if user has access to a session
     *
     * @param string $attribute
     * @param Session $session
     * @param User $user
     * @return bool
     */
    protected function isGranted($attribute, $session, $user = null)
    {
        // make sure there is a user object (i.e. that the user is logged in)
        if (!$user instanceof UserInterface) {
            return false;
        }

        // Checks if the current user was set up
        $course = $session->getCurrentCourse();

        if ($course == false) {

            return false;
        }

        $authChecker = $this->container->get('security.authorization_checker');

        // Admins have access to everything
        if ($authChecker->isGranted('ROLE_ADMIN')) {

            return true;
        }

        if (!$session->isActive()) {

            return false;
        }

        switch ($attribute) {
            case self::VIEW:
                if (!$session->hasUserInCourse($user, $course)) {
                    $user->addRole('ROLE_CURRENT_SESSION_COURSE_STUDENT');

                    return true;
                }

                break;
            case self::EDIT:
            case self::DELETE:

                // General coach check
                $generalCoach = $session->getGeneralCoach();
                if ($generalCoach) {
                    $coachId = $generalCoach->getId();
                    $userId = $user->getId();
                    if ($coachId == $userId) {
                        $user->addRole('ROLE_CURRENT_SESSION_COURSE_TEACHER');

                        return true;
                    }
                }

                // Course session coach check
                if (!$session->hasCoachInCourseWithStatus($user, $course)) {

                    $user->addRole('ROLE_CURRENT_SESSION_COURSE_TEACHER');

                    return true;
                }
                break;
        }

        // User don't have access to the session
        return false;
    }
}

