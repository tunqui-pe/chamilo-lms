<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\CoreBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

/**
 * Class CourseMenuBuilder
 * @package Chamilo\CoreBundle\Menu
 */
class CourseMenuBuilder extends ContainerAware
{
    /**
     * @param FactoryInterface $factory
     * @param array $options
     * @return \Knp\Menu\ItemInterface
     */
    public function courseMenu(FactoryInterface $factory, array $options)
    {
        $security = $this->container->get('security.authorization_checker');
        $menu = $factory->createItem('root');
        $translator = $this->container->get('translator');

        if ($security->isGranted('IS_AUTHENTICATED_FULLY')) {

            $menu->setChildrenAttribute('class', 'nav nav-pills nav-stacked');

            $menu->addChild(
                $translator->trans('CreateCourse'),
                ['route' => 'main', 'routeParameters' => ['name'=> 'create_course/add_course.php']]
            );

            $menu->addChild(
                $translator->trans('AddSession'),
                [
                    'route' => 'main',
                    'routeParameters' => ['name' => 'session/session_add.php'],
                ]
            );

            /*$menu->addChild(
                $translator->trans('History'),
                ['route' => 'main', 'routeParameters' => ['name'=> 'auth/courses.php?action=sortmycourses']]
            );*/

            $menu->addChild(
                $translator->trans('CourseCatalog'),
                [
                    'route' => 'main',
                    'routeParameters' => ['name' => 'auth/courses.php'],
                ]
            );
        }
        return $menu;
    }

    /**
     * @param FactoryInterface $factory
     * @param array $options
     * @return \Knp\Menu\ItemInterface
     */
    public function skillsMenu(FactoryInterface $factory, array $options)
    {
        $security = $this->container->get('security.authorization_checker');
        $translator = $this->container->get('translator');
        $menu = $factory->createItem('root');
        if ($security->isGranted('IS_AUTHENTICATED_FULLY')) {

            $menu->setChildrenAttribute('class', 'nav nav-pills nav-stacked');

            $menu->addChild(
                $translator->trans('MyCertificates'),
                [
                    'route' => 'main',
                    'routeParameters' => ['name' => 'gradebook/my_certificates.php'],
                ]
            );

            $menu->addChild(
                $translator->trans('Search'),
                [
                    'route' => 'main',
                    'routeParameters' => ['name' => 'gradebook/search.php'],
                ]
            );

            $menu->addChild(
                $translator->trans('MySkills'),
                [
                    'route' => 'main',
                    'routeParameters' => ['name' => 'social/my_skills_report.php'],
                ]
            );

            $menu->addChild(
                $translator->trans('ManageSkills'),
                [
                    'route' => 'main',
                    'routeParameters' => ['name' => 'admin/skills_wheel.php'],
                ]
            );
        }

        return $menu;
    }
}
