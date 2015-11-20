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
    public function courseMenu(FactoryInterface $factory, array $options)
    {
        $security = $this->container->get('security.context');
        $menu = $factory->createItem('root');
        if ($security->isGranted('IS_AUTHENTICATED_FULLY')) {

            $menu->setChildrenAttribute('class', 'nav nav-pills nav-stacked');

            $menu->addChild(
                'Create course',
                ['route' => 'main', 'routeParameters' => ['name'=> 'create_course/add_course.php']]
            );
            //$menu->addChild('Catalog', array('route' => 'logout'));
            //$menu->addChild('History', array('route' => 'logout'));
        }
        return $menu;
    }

    public function skillsMenu(FactoryInterface $factory, array $options)
    {
        $security = $this->container->get('security.context');
        $menu = $factory->createItem('root');
        if ($security->isGranted('IS_AUTHENTICATED_FULLY')) {

            $menu->setChildrenAttribute('class', 'nav nav-pills nav-stacked');

            $menu->addChild(
                'My certificates',
                [
                    'route' => 'main',
                    'routeParameters' => ['name' => 'gradebook/my_certificates.php'],
                ]
            );

            $menu->addChild(
                'My certificates',
                [
                    'route' => 'main',
                    'routeParameters' => ['name' => 'gradebook/my_certificates.php'],
                ]
            );

            $menu->addChild(
                'Search',
                [
                    'route' => 'main',
                    'routeParameters' => ['name' => 'gradebook/my_certificates.php'],
                ]
            );

            $menu->addChild(
                'My skills',
                [
                    'route' => 'main',
                    'routeParameters' => ['name' => 'gradebook/my_certificates.php'],
                ]
            );

            $menu->addChild(
                'Manage skills',
                [
                    'route' => 'main',
                    'routeParameters' => ['name' => 'gradebook/my_certificates.php'],
                ]
            );
        }

        return $menu;
    }
}
