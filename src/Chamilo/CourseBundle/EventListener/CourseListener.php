<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\CourseBundle\EventListener;

use Chamilo\CoreBundle\Controller\LegacyController;
use Chamilo\CoreBundle\Security\Authorization\Voter\CourseVoter;
use Chamilo\CoreBundle\Security\Authorization\Voter\SessionVoter;
use Chamilo\CoreBundle\Security\Authorization\Voter\GroupVoter;
use Chamilo\CoreBundle\Framework\Container;
use Doctrine\ORM\EntityManager;
use Chamilo\UserBundle\Entity\User;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Chamilo\CourseBundle\Controller\ToolInterface;
use Chamilo\CoreBundle\Entity\Course;
use Chamilo\CoreBundle\Entity\Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Class CourseListener
 * @package Chamilo\CourseBundle\EventListener
 */
class CourseListener
{
    /** @var ContainerInterface */
    protected $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
    }

    /**
     * @param FilterResponseEvent $event
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
    }

    /**
     * @param FilterControllerEvent $event
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        $controllerList = $event->getController();

        if (!is_array($controllerList)) {
            return;
        }

        // This controller implements ToolInterface? Then set the course/session
        if (is_array($controllerList) && (
                $controllerList[0] instanceof ToolInterface ||
                $controllerList[0] instanceof LegacyController
            )
        ) {
            $controller = $controllerList[0];

            //if ($controller[0] instanceof ToolBaseController) {
            //$token = $event->getRequest()->query->get('token');
            //$kernel = $event->getKernel();
            $request = $event->getRequest();
            $sessionHandler = $this->container->get('session');

            /** @var ContainerInterface $container */
            $container = $this->container;
            $translator = $this->container->get('translator.default');

            // Course
            // The 'course' variable example "123" for this URL: courses/123/
            $courseCode = $request->get('course');

            // Detect if the course was set with a cidReq:
            if (empty($courseCode)) {
                $courseCodeFromRequest = $request->get('cidReq');
                $courseCode = $courseCodeFromRequest;
            }

            /** @var EntityManager $em */
            $em = $container->get('doctrine')->getManager();

            $securityChecker = $container->get('security.authorization_checker');
            $tokenStorage = $container->get('security.token_storage');
            $token = $tokenStorage->getToken();
            //$user = $token->getUser();

            if (!empty($courseCode)) {
                /** @var Course $course */
                $course = $em->getRepository('ChamiloCoreBundle:Course')->findOneByCode($courseCode);

                if ($course) {
                    // Session
                    $sessionId = intval($request->get('id_session'));

                    // Group
                    $groupId = intval($request->get('gidReq'));
                    if (empty($sessionId)) {
                        // Check if user is allowed to this course
                        // See CourseVoter.php
                        if (false === $securityChecker->isGranted(CourseVoter::VIEW, $course)) {
                            throw new AccessDeniedException(
                                $translator->trans(
                                    'Unauthorised access to course!'
                                )
                            );
                        }
                    } else {
                        $session = $em->getRepository('ChamiloCoreBundle:Session')->find($sessionId);
                        if ($session) {
                            //$course->setCurrentSession($session);
                            $controller->setSession($session);
                            $session->setCurrentCourse($course);
                            // Check if user is allowed to this course-session
                            // See SessionVoter.php
                            if (false === $securityChecker->isGranted(SessionVoter::VIEW, $session)) {
                                throw new AccessDeniedException(
                                    $translator->trans('Unauthorised access to session!')
                                );
                            }

                            $request->getSession()->set(
                                'session_name',
                                $session->getName()
                            );

                            $request->getSession()->set(
                                'id_session',
                                $session->getId()
                            );

                        } else {
                            throw new NotFoundHttpException($translator->trans('Session not found'));
                        }
                    }

                    if (!empty($groupId)) {
                        $group = $em->getRepository(
                            'ChamiloCourseBundle:CGroupInfo'
                        )->find($groupId);
                        if ($course->hasGroup($group)) {
                            if ($group) {
                                // Check if user is allowed to this course-group
                                // See GroupVoter.php
                                if (false === $securityChecker->isGranted(
                                        GroupVoter::VIEW,
                                        $group
                                    )
                                ) {
                                    throw new AccessDeniedException(
                                        $translator->trans('Unauthorised access to group')
                                    );
                                }
                            } else {
                                throw new NotFoundHttpException(
                                    $translator->trans('Group not found')
                                );
                            }
                        } else {
                            throw new AccessDeniedException(
                                $translator->trans('Group does not exist in course')
                            );
                        }
                    }

                    // Example 'chamilo_notebook.controller.notebook:indexAction'
                    $controllerAction = $request->get('_controller');
                    $controllerActionParts = explode(':', $controllerAction);
                    $controllerNameParts = explode('.', $controllerActionParts[0]);
                    $controllerName = $controllerActionParts[0];

                    $toolName = null;
                    $toolAction = null;
                    if (isset($controllerNameParts[1]) &&
                        $controllerNameParts[1] == 'controller') {
                        $toolName = $this->container->get($controllerName)->getToolName();
                        $action = str_replace('action', '', $controllerActionParts[1]);
                        $toolAction = $toolName.'.'.$action;
                    }

                    $container->get('twig')->addGlobal('tool.name', $toolName);
                    $container->get('twig')->addGlobal('tool.action', $toolAction);

                    // Legacy code

                    $courseInfo = api_get_course_info($course->getCode());
                    $container->get('twig')->addGlobal('course', $course);

                    $sessionHandler->set('_real_cid', $course->getId());
                    $sessionHandler->set('_cid', $course->getCode());
                    $sessionHandler->set('_course', $courseInfo);
                    $sessionHandler->set('_gid', $groupId);
                    $sessionHandler->set('is_allowed_in_course', true);
                    $sessionHandler->set('id_session', $sessionId);

                    // Sets the controller course in order to use $this->getCourse()
                    $controller->setCourse($course);
                } else {
                    throw new NotFoundHttpException(
                        $translator->trans('CourseDoesNotExist')
                    );
                }
            }
        }
    }
}
