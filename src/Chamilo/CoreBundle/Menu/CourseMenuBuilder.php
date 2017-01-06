<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\CoreBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * Class CourseMenuBuilder
 * @package Chamilo\CoreBundle\Menu
 */
class CourseMenuBuilder implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * Course menu
     *
     * @param FactoryInterface $factory
     * @param array $options
     * @return \Knp\Menu\ItemInterface
     */
    public function courseMenu(FactoryInterface $factory, array $options)
    {
        $checker = $this->container->get('security.authorization_checker');
        $menu = $factory->createItem('root');
        $translator = $this->container->get('translator');
        $settingsManager = $this->container->get('chamilo.settings.manager');

        if ($checker->isGranted('IS_AUTHENTICATED_FULLY')) {
            $menu->setChildrenAttribute('class', 'nav nav-pills nav-stacked');
            $menu->addChild(
                $translator->trans('MyCourses'),
                [
                    'route' => 'userportal',
                    'routeParameters' => ['type' => 'courses'],
                ]
            );

            $menu->addChild(
                $translator->trans('MySessions'),
                [
                    'route' => 'userportal',
                    'routeParameters' => ['type' => 'sessions'],
                ]
            );

            if (api_is_allowed_to_create_course()) {
                $lang = $translator->trans('CreateCourse');
                if ($settingsManager->getSetting(
                        'course.course_validation'
                    ) == 'true'
                ) {
                    $lang = $translator->trans('CreateCourseRequest');
                }
                $menu->addChild(
                    $lang,
                    ['route' => 'add_course']
                );
            }

            if ($checker->isGranted('ROLE_TEACHER') &&
                $settingsManager->getSetting(
                    'session.allow_teachers_to_create_sessions'
                )
            ) {

                $menu->addChild(
                    $translator->trans('AddSession'),
                    [
                        'route' => 'main',
                        'routeParameters' => ['name' => 'session/session_add.php'],
                    ]
                );
            }

            $link = $this->container->get('router')->generate('web.main');

            $menu->addChild(
                $translator->trans('ManageCourses'),
                [
                    'uri' => $link.'auth/courses.php?action=sortmycourses',
                ]
            );

            $browse = $settingsManager->getSetting(
                'display.allow_students_to_browse_courses'
            );

            if ($browse == 'true') {
                if ($checker->isGranted('ROLE_STUDENT') && !api_is_drh(
                    ) && !api_is_session_admin()
                ) {
                    $menu->addChild(
                        $translator->trans('CourseCatalog'),
                        [
                            'uri' => $link.'auth/courses.php',
                        ]
                    );
                } else {
                    $menu->addChild(
                        $translator->trans('Dashboard'),
                        [
                            'uri' => $link.'dashboard/index.php',
                        ]
                    );
                }
            }

            /** @var \Knp\Menu\MenuItem $menu */
            $menu->addChild(
                $translator->trans('History'),
                [
                    'route' => 'userportal',
                    'routeParameters' => [
                        'type' => 'sessions',
                        'filter' => 'history',
                    ],
                ]
            );

        }

        return $menu;
    }

    /**
     * Skills menu
     * @param FactoryInterface $factory
     * @param array $options
     * @return \Knp\Menu\ItemInterface
     */
    public function skillsMenu(FactoryInterface $factory, array $options)
    {
        $checker = $this->container->get('security.authorization_checker');
        $translator = $this->container->get('translator');
        $settingsManager = $this->container->get('chamilo.settings.manager');
        //$allow = $settingsManager->getSetting('hide_my_certificate_link');
        $allow = api_get_configuration_value('hide_my_certificate_link');

        $menu = $factory->createItem('root');
        if ($checker->isGranted('IS_AUTHENTICATED_FULLY')) {
            $menu->setChildrenAttribute('class', 'nav nav-pills nav-stacked');

            if ($allow == false) {
                $menu->addChild(
                    $translator->trans('MyCertificates'),
                    [
                        'route' => 'main',
                        'routeParameters' => ['name' => 'gradebook/my_certificates.php'],
                    ]
                );
            }

            if ($settingsManager->getSetting(
                    'allow_public_certificates'
                ) === 'true'
            ) {
                $menu->addChild(
                    $translator->trans('Search'),
                    [
                        'route' => 'main',
                        'routeParameters' => ['name' => 'gradebook/search.php'],
                    ]
                );
            }

            if ($settingsManager->getSetting('allow_skills_tool') === 'true') {
                $menu->addChild(
                    $translator->trans('MySkills'),
                    [
                        'route' => 'main',
                        'routeParameters' => ['name' => 'social/my_skills_report.php'],
                    ]
                );

                if ($checker->isGranted('ROLE_TEACHER')) {
                    $menu->addChild(
                        $translator->trans('ManageSkills'),
                        [
                            'route' => 'main',
                            'routeParameters' => ['name' => 'admin/skills_wheel.php'],
                        ]
                    );
                }
            }
        }

        return $menu;
    }
}
