<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\CoreBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * Class SimpleMenuBuilder
 *
 * @package Sonata\Bundle\DemoBundle\Menu
 *
 * @author Hugo Briand <briand@ekino.com>
 */
class SimpleMenuBuilder implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * Register/reset password menu
     * @todo
     * @param FactoryInterface $factory
     * @param array $options
     * @return \Knp\Menu\ItemInterface
     */
    public function notLoginMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('main');
        $translator = $this->container->get('translator.default');
        $settingManager = $this->container->get('chamilo.settings.manager');

        if ($settingManager->getSetting('allow_registration') == 'true') {
            $menu->addChild(
                $translator->trans(
                    'registration.submit',
                    array(),
                    'FOSUserBundle'
                ),
                array(
                    'route' => 'main',
                    'routeParameters' => ['name' => 'auth/inscription.php'],
                    array("attributes" => array("id" => 'nav'))
                )
            );
        }

        if ($settingManager->getSetting('allow_lostpassword') == 'true') {
            $menu->addChild(
                $translator->trans(
                    'resetting.request.submit',
                    array(),
                    'FOSUserBundle'
                ),
                array(
                    //'route' => 'fos_user_resetting_request',
                    'route' => 'main',
                    'routeParameters' => ['name' => 'auth/lostPassword.php'],
                    array("attributes" => array("id" => 'nav'))
                )
            );
        }

        return $menu;
    }

//    public function helpMenu(FactoryInterface $factory, array $options)
//    {
//        $menu = $factory->createItem('main');
//        $menu->addChild(
//            'Help',
//            array(
//                'route' => 'userportal',
//                array("attributes" => array("id" => 'nav'))
//            )
//        );
//
//        return $menu;
//    }

    /**
     * Creates the header menu
     *
     * @param FactoryInterface $factory
     * @param array            $options
     *
     * @return \Knp\Menu\ItemInterface
     */
    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $isFooter = array_key_exists('is_footer', $options) ? $options['is_footer'] : false;

        $menu = $factory->createItem('main');

        $child = $menu->addChild(
            'My courses',
            array(
                'route' => 'userportal',
                array("attributes" => array("id" => 'nav'))
            )
        );

        $menu->addChild(
            'Progress',
            array(
                'route' => 'main',
                'routeParameters' => array('name' => 'mySpace/index.php'),
                array("attributes" => array("id" => 'nav'))
            )
        );

        $menu->addChild(
            'Calendar',
            array(
                'route' => 'main',
                'routeParameters' => array('name' => 'calendar/agenda_js.php'),
                array("attributes" => array("id" => 'nav'))
            )
        );
        return $menu;
    }

    public function footerMenu(FactoryInterface $factory, array $options)
    {
        return $this->mainMenu($factory, array_merge($options, array('is_footer' => true)));
    }

    public function getIdentifier()
    {
        return 'simple_menu';
    }

    /**
     * @return mixed
     */
    public function getLabel()
    {
        return 'label';
    }

    /**
     * @return mixed
     */
    public function getRoute()
    {
        return 'root';
    }

    /**
     * @return mixed
     */
    public function isActive()
    {
        return true;
    }

    /**
     * @param $isActive
     *
     * @return mixed
     */
    public function setIsActive($isActive)
    {
        //$isActive
    }

    /**
     * @return mixed
     */
    public function hasChildren()
    {
        return;
    }

}
