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
}
