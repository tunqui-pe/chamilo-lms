<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\CoreBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Chamilo\CoreBundle\Framework\Container;

/**
 * Class LegacyListener
 * Adds objects into the session like the old global.inc
 * @package Chamilo\CoreBundle\EventListener
 */
class LegacyListener
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
        $session = $request->getSession();

        /** @var ContainerInterface $container */
        $container = $this->container;

        // Setting container
        Container::setContainer($container);

        // Setting database.
        $connection = $container->get('database_connection');

        // Setting DB connection and Doctrine Manager.
        $database = new \Database();
        $database->setConnection($connection);
        $entityManager = $container->get('doctrine')->getManager();
        $database->setManager($entityManager);

        \CourseManager::setEntityManager($entityManager);
        \CourseManager::setCourseSettingsManager($container->get('chamilo_course.settings.manager'));

        // Legacy way of detect current access_url
        $installed = $this->container->getParameter('installed');
        $urlId = 1;
        if (!empty($installed)) {
            $access_urls = api_get_access_urls();
            $root_rel = api_get_self();
            $root_rel = substr($root_rel, 1);
            $pos = strpos($root_rel, '/');
            $root_rel = substr($root_rel, 0, $pos);
            $protocol = ((!empty($_SERVER['HTTPS']) && strtoupper(
                        $_SERVER['HTTPS']
                    ) != 'OFF') ? 'https' : 'http').'://';
            //urls with subdomains (HTTP_HOST is preferred - see #6764)
            if (empty($_SERVER['HTTP_HOST'])) {
                if (empty($_SERVER['SERVER_NAME'])) {
                    $request_url_root = $protocol.'localhost/';
                } else {
                    $request_url_root = $protocol.$_SERVER['SERVER_NAME'].'/';
                }
            } else {
                $request_url_root = $protocol.$_SERVER['HTTP_HOST'].'/';
            }
            //urls with subdirs
            $request_url_sub = $request_url_root.$root_rel.'/';

            // You can use subdirs as multi-urls, but in this case none of them can be
            // the root dir. The admin portal should be something like https://host/adm/
            // At this time, subdirs will still hold a share cookie, so not ideal yet
            // see #6510
            $urlId = 1;
            foreach ($access_urls as $details) {
                if ($request_url_sub == $details['url']) {
                    $urlId = $details['id'];
                    break; //found one match with subdir, get out of foreach
                }
                // Didn't find any? Now try without subdirs
                if ($request_url_root == $details['url']) {
                    $urlId = $details['id'];
                    break; //found one match, get out of foreach
                }
            }
        }

        $session->set('access_url_id', $urlId);

        // Setting course tool chain (in order to create tools to a course)
        \CourseManager::setToolList(
            $container->get('chamilo_course.tool_chain')
        );

        \CourseManager::setCourseManager(
            $container->get('chamilo_core.manager.course')
        );

        // Setting legacy properties.
        Container::$dataDir = $container->get('kernel')->getDataDir();
        Container::$courseDir = $container->get('kernel')->getDataDir();
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

    }
}
