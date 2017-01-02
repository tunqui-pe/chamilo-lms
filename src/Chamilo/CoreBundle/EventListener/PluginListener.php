<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\CoreBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Chamilo\CoreBundle\Framework\Container;

/**
 * Class PluginListener
 * @package Chamilo\CoreBundle\EventListener
 */
class PluginListener
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
        $request = $event->getRequest();
        $controller = $request->get('_controller');
        // Only process legacy listener when loading legacy controller
        /*if ($controller != 'Chamilo\CoreBundle\Controller\LegacyController::classicAction') {
            return;
        }*/

        $skipControllers = [
            'web_profiler.controller.profiler:toolbarAction', //
            'fos_js_routing.controller:indexAction'//
        ];

        // Skip legacy listener
        if (in_array($controller, $skipControllers)) {
            return;
        }

        // Legacy way of detect current access_url

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
        $request = $event->getRequest();
        $controller = $request->get('_controller');
        // Only process legacy listener when loading legacy controller
        /*if ($controller != 'Chamilo\CoreBundle\Controller\LegacyController::classicAction') {
            return;
        }*/

        $skipControllers = [
            'web_profiler.controller.profiler:toolbarAction', // debug toolbar
            'fos_js_routing.controller:indexAction' // js/routing?callback=fos.Router.setData
        ];

        // Skip legacy listener
        if (in_array($controller, $skipControllers)) {
            return;
        }

        $request = $event->getRequest();
        /** @var ContainerInterface $container */
        $container = $this->container;


        if (!$request->hasPreviousSession()) {

            return;
        }

        $controller = $request->get('_controller');

        $installed = $this->container->getParameter('installed');
        $urlId = 1;
        if (!empty($installed)) {
            //$result = & api_get_settings('Plugins', 'list', $_configuration['access_url']);
            $result = & api_get_settings('Plugins', 'list', 1);

            $_plugins = array();
            foreach ($result as & $row) {
                $key = $row['variable'];
                $_plugins[$key][] = $row['selected_value'];
            }

            // Loading Chamilo plugins
            $appPlugin = new \AppPlugin();
            $pluginRegions = $appPlugin->get_plugin_regions();

            $force_plugin_load = true;
            $pluginList = $appPlugin->get_installed_plugins();

            foreach ($pluginRegions as $pluginRegion) {
                $regionContent = $appPlugin->load_region(
                    $pluginRegion,
                    $container->get('twig'),
                    $_plugins,
                    $force_plugin_load
                );

                foreach ($pluginList as $plugin_name) {
                    // The plugin_info variable is available inside the plugin index
                    $pluginInfo = $appPlugin->getPluginInfo($plugin_name);
                    if (isset($pluginInfo['is_course_plugin']) && $pluginInfo['is_course_plugin']) {
                        $courseInfo = api_get_course_info();
                        if (!empty($courseInfo)) {
                            if (isset($pluginInfo['obj']) && $pluginInfo['obj'] instanceof \Plugin) {
                                /** @var \Plugin $plugin */
                                $plugin = $pluginInfo['obj'];
                                $regionContent .= $plugin->renderRegion($pluginRegion);
                            }
                        }
                    } else {
                        continue;
                    }
                }

                if (!empty($regionContent)) {
                    $container->get('twig')->addGlobal('plugin_'.$pluginRegion, $regionContent);
                } else {
                    $container->get('twig')->addGlobal('plugin_'.$pluginRegion, '');
                }
            }
        }
    }
}
