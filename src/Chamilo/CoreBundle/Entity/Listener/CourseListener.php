<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\CoreBundle\Entity\Listener;

use Chamilo\CoreBundle\Entity\AccessUrl;
use Chamilo\CoreBundle\Entity\AccessUrlRelCourse;
use Chamilo\CoreBundle\Entity\Tool;
use Chamilo\CourseBundle\ToolChain;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Chamilo\CoreBundle\Entity\Course;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class CourseListener
 * Course entity listener, when a course is created the tool chain is loaded.
 * @package Chamilo\CoreBundle\EventListener
 */
class CourseListener
{
    protected $toolChain;

    /**
     * @param ToolChain $toolChain
     */
    public function __construct(ToolChain $toolChain)
    {
        $this->toolChain = $toolChain;
    }

    /**
     * This code is executed when a new course is created.
     *
     * new object : prePersist
     * edited object: preUpdate
     *
     * @param Course $course
     * @param LifecycleEventArgs $args
     *
     * @throws \Exception
     */
    public function prePersist(Course $course, LifecycleEventArgs $args)
    {
        /** @var AccessUrlRelCourse $urlRelCourse */
        $urlRelCourse = $course->getUrls()->first();
        $url = $urlRelCourse->getUrl();

        $repo = $args->getEntityManager()->getRepository('ChamiloCoreBundle:Course');
        $limit = $url->getLimitCourses();

        if (!empty($limit)) {
            $count = $repo->getCountCoursesByUrl($url);
            if ($count >= $limit) {
                throw new \Exception('Limit courses reached');
            }
        }

        if ($course->getVisibility() != COURSE_VISIBILITY_HIDDEN) {
            $limit = $url->getLimitActiveCourses();

            if (!empty($limit)) {
                $count = $repo->getCountActiveCoursesByUrl($url);
                if ($count >= $limit) {
                    throw new \Exception('Limit active courses reached');
                }
            }
        }

        $this->toolChain->addToolsInCourse($course);
        /*
        error_log('ddd');
        $course->setDescription( ' dq sdqs dqs dqs ');

        $args->getEntityManager()->persist($course);
        $args->getEntityManager()->flush();*/
    }

    /**
     * This code is executed when a course is updated
     *
     * @param Course $course
     * @param LifecycleEventArgs $args
     */
    public function preUpdate(Course $course, LifecycleEventArgs $args)
    {
        $url = $course->getCurrentUrl();

        $repo = $args->getEntityManager()->getRepository('ChamiloCoreBundle:Course');
        $limit = $url->getLimitCourses();

        if (!empty($limit)) {
            $count = $repo->getCountCoursesByUrl($url);
            if ($count >= $limit) {
                throw new \Exception('Limit courses reached');
            }
        }

        if ($course->getVisibility() != COURSE_VISIBILITY_HIDDEN) {
            $limit = $url->getLimitActiveCourses();

            if (!empty($limit)) {
                $count = $repo->getCountActiveCoursesByUrl($url);
                if ($count >= $limit) {
                    throw new \Exception('Limit active courses reached');
                }
            }
        }

        /*if ($eventArgs->getEntity() instanceof User) {
            if ($eventArgs->hasChangedField('name') && $eventArgs->getNewValue('name') == 'Alice') {
                $eventArgs->setNewValue('name', 'Bob');
            }
        }*/
    }
}
