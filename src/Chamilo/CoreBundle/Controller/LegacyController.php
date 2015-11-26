<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\CoreBundle\Controller;

use Chamilo\CourseBundle\Controller\ToolBaseController;
use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Chamilo\CoreBundle\Framework\Container;
use Display;

/**
 * Class LegacyController
 * Manages the chamilo pages starting with Display::display_header and $tpl = new Template();
 * @package Chamilo\CoreBundle\Controller
 * @author Julio Montoya <gugli100@gmail.com>
 */
class LegacyController extends ToolBaseController
{
    public $section;

    /**
     * @param string $name
     * @param Request $request
     * @return Response
     */
    public function classicAction($name, Request $request)
    {
        // get.
        $_GET = $request->query->all();
        // post.
        $_POST = $request->request->all();

        $rootDir = $this->get('kernel')->getRealRootDir();

        // Legacy require files
        require api_get_path(LIBRARY_PATH).'fileManage.lib.php';
        require api_get_path(LIBRARY_PATH).'fileUpload.lib.php';
        require api_get_path(LIBRARY_PATH).'fileDisplay.lib.php';

        //$_REQUEST = $request->request->all();
        $mainPath = $rootDir.'main/';
        $fileToLoad = $mainPath.$name;

        // Setting legacy values inside the container

        /** @var Connection $dbConnection */
        $dbConnection = $this->container->get('database_connection');
        $em = $this->get('kernel')->getContainer()->get('doctrine.orm.entity_manager');

        $database = new \Database($dbConnection, array());

        $database->setConnection($dbConnection);
        $database->setManager($em);
        Container::$container = $this->container;
        Container::$dataDir = $this->container->get('kernel')->getDataDir();
        Container::$courseDir = $this->container->get('kernel')->getDataDir();
        //Container::$configDir = $this->container->get('kernel')->getConfigDir();
        $this->container->get('twig')->addGlobal('show_header', true);

        //$breadcrumb = $this->container->get('chamilo_core.block.breadcrumb');

        if (is_file($fileToLoad) &&
            \Security::check_abs_path($fileToLoad, $mainPath)
        ) {
            // Files inside /main need this variables to be set
            $is_allowed_in_course = api_is_allowed_in_course();
            $is_courseAdmin = api_is_course_admin();
            $is_platformAdmin = api_is_platform_admin();

            $toolNameFromFile = basename(dirname($fileToLoad));
            $charset = 'UTF-8';
            // Default values
            $_course = api_get_course_info();
            $_user = api_get_user_info();
            $debug = $this->container->get('kernel')->getEnvironment() == 'dev' ? true : false;

            // Loading file
            ob_start();
            require_once $fileToLoad;
            $out = ob_get_contents();
            ob_end_clean();

            // No browser cache when executing an exercise.
            if ($name == 'exercice/exercise_submit.php') {
                $responseHeaders = array(
                    'cache-control' => 'no-store, no-cache, must-revalidate'
                );
            }

            $js = isset($htmlHeadXtra) ? $htmlHeadXtra : array();

            // $interbreadcrumb is loaded in the require_once file.
            $interbreadcrumb = isset($interbreadcrumb) ? $interbreadcrumb : null;

            $template = Container::$legacyTemplate;
            $defaultLayout = 'layout_one_col.html.twig';
            if (!empty($template)) {
                $defaultLayout = $template;
            }

            return $this->render(
                'ChamiloCoreBundle::'.$defaultLayout,
                array(
                    'legacy_breadcrumb' => $interbreadcrumb,
                    'content' => $out,
                    'js' => $js,
                    'menu' => ''
                )
            );
        } else {
            // Found does not exist
            throw new NotFoundHttpException();
        }
    }
}
