<?php

namespace Chamilo\CmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Chamilo\CmsBundle\Document\Page;

/**
 * Class DefaultController
 * @package Chamilo\CmsBundle\Controller
 */
class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render(
            'ChamiloCmsBundle:Default:index.html.twig',
            array('name' => $name)
        );
    }

    /**
     * @param Page $contentDocument
     *
     * @Template()
     */
    public function pageAction($contentDocument)
    {
        echo $contentDocument->getLocale();
        $dm = $this->get('doctrine_phpcr')->getManager();
        $posts = $dm->getRepository('ChamiloCmsBundle:Post')->findAll();

        return array(
            'page' => $contentDocument,
            'posts' => $posts,
        );
    }

    /**
     * @Template()
     */
    public function postAction($contentDocument)
    {
        $dm = $this->get('doctrine_phpcr')->getManager();

        return array(
            'post' => $contentDocument,
        );
    }

}
