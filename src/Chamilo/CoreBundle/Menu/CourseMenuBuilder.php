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

    /**
     * @param FactoryInterface $factory
     * @param array $options
     * @return \Knp\Menu\ItemInterface
     */
    public function skillsMenu(FactoryInterface $factory, array $options)
    {
        $security = $this->container->get('security.context');
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
                    'routeParameters' => ['name' => 'grgradebook/search.phpadebook/search.php'],
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
