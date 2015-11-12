<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\CourseBundle\Controller\Introduction;

use Chamilo\CoreBundle\Component\Editor\CkEditor\Toolbar\Introduction;
use Chamilo\CourseBundle\Controller\ToolBaseController;
use Chamilo\CoreBundle\Entity\CToolIntro;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Chamilo\CoreBundle\Controller\CrudController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class IntroductionToolController
 * @package Chamilo\CourseBundle\Controller\Introduction
 * @author Julio Montoya <gugli100@gmail.com>
 * @Route("/introduction")
 */
class IntroductionController extends ToolBaseController
{
    /**
     * @Route("/edit/{tool}")
     * @Method({"GET|POST"})
     * @Template("ChamiloCourseBundle:Introduction:index.html.twig")
     * @param string $tool
     * @return Response
     */
    public function editAction($tool, Request $request)
    {
        $message = null;
        // @todo use proper functions not api functions.
        $courseId = api_get_course_int_id();
        $sessionId = api_get_session_id();
        $tool = \Database::escape_string($tool);
        $table = \Database::get_course_table(TABLE_TOOL_INTRO);

        $url = $this->generateUrl(
            'chamilo_course_introduction_introduction_edit',
            array('tool' => $tool, 'course' => api_get_course_id())
        );

        $form = $this->getFormValidator($url, $tool);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $values = $form->getData();
            $content = $values['content'];

            $sql = "REPLACE $table
                    SET c_id = $courseId,
                        id = '$tool',
                        intro_text='".\Database::escape_string($content)."',
                        session_id='".intval($sessionId)."'";
            \Database::query($sql);

            $this->addFlash(
                'confirmation',
                $this->trans('IntroductionTextUpdated')
            );
        } else {

            $sql = "SELECT intro_text FROM $table
                    WHERE
                      c_id = $courseId AND
                      id='".$tool."' AND
                      session_id = '".intval($sessionId)."'";
            $result = \Database::query($sql);
            $content = null;
            if (\Database::num_rows($result) > 0) {
                $row = \Database::fetch_array($result);
                $content = $row['intro_text'];
            }
            $form->setData(array('content' => $content));
        }

        return array(
            'title' => $tool,
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/delete/{tool}")
     * @Method({"GET"})
     *
     * @param string $tool
     * @param Request $request
     * @return Response
     *
     */
    public function deleteAction($tool, Request $request)
    {
        $courseId = $this->getCourse()->getId();
        $sessionId = $request->get('sessionId');
        $criteria = array(
            'sessionId' => intval($sessionId),
            'id' => $tool,
            'cId' => $courseId,
        );

        $doctrine = $this->getDoctrine();
        $toolIntro = $doctrine->getRepository(
            'ChamiloCourseBundle:CToolIntro'
        )->findOneBy($criteria);
        if ($toolIntro) {
            $doctrine->getManager()->remove($toolIntro);
            $doctrine->getManager()->flush();

            $this->addFlash('success', $this->trans("IntroductionTextDeleted"));
        }

        return $this->redirectCourseHome();
    }

    /**
     *
     * @param string $url
     * @param string $tool
     * @return \Symfony\Component\Form\Form
     */
    public function getFormValidator($url, $tool)
    {
        $form = $this->createFormBuilder(
            null,
            [
                'action' => $url,
            ]
        );

        $toolbar = new Introduction('');
        $config = $toolbar->getConfig();

        $form->add('content', 'ckeditor', ['config' => $config]);

        //$form = new \FormValidator('form', 'post', $url);
        //$form->addHtmlEditor('content', null, null, false, $config);
        if ($tool == 'course_homepage') {
            /*$form->add(get_lang('YouCanUseAllTheseTags'),
                ''
                '(('.implode(')) <br /> ((', \CourseHome::availableTools()).'))'
            );*/
        }
        $form->add('save', 'submit', ['label' => get_lang('SaveIntroText')]);

        return $form->getForm();
    }

}
