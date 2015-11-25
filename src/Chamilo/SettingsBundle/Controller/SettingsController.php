<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\SettingsBundle\Controller;

use Sylius\Bundle\SettingsBundle\Controller\SettingsController as SyliusSettingsController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Exception\ValidatorException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Chamilo\SettingsBundle\Manager\SettingsManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Class SettingsController
 * @package Chamilo\SettingsBundle\Controller
 */
class SettingsController extends SyliusSettingsController
{
    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Template
     * @return array
     */
    public function indexAction()
    {
        $manager = $this->getSettingsManager();
        $schemas = $manager->getSchemas();

        return array('schemas' => $schemas);
    }

    /**
     * Edit configuration with given namespace.
     * @Security("has_role('ROLE_ADMIN')")
     * @param Request $request
     * @param string $namespace
     *
     * @return Response
     */
    public function updateSettingAction(Request $request, $namespace)
    {
        $manager = $this->getSettingsManager();

        $builder = $this->container->get('form.factory')->createNamedBuilder(
            'search'
        );
        $builder->add('keyword', 'text');
        $builder->add('search', 'submit');
        $searchForm = $builder->getForm();

        $keyword = '';
        if ($searchForm->handleRequest($request)->isValid()) {
            $values = $searchForm->getData();
            $keyword = $values['keyword'];
            $settingsFromKeyword = $manager->getParametersFromKeyword(
                $namespace,
                $keyword
            );
        }

        $keywordFromGet = $request->query->get('keyword');
        if ($keywordFromGet) {
            $keyword = $keywordFromGet;
            $searchForm->setData(['keyword' => $keyword]);
            $settingsFromKeyword = $manager->getParametersFromKeyword(
                $namespace,
                $keywordFromGet
            );
        }

        $settings = $manager->loadSettings($namespace);

        $form = $this
            ->getSettingsFormFactory()
            ->create($namespace)
        ;

        //$form->add('keyword', 'hidden', ['data' => $keyword]);

        if (!empty($keyword)) {
            $params = $settings->getParameters();
            foreach ($params as $name => $value) {
                if (!in_array($name, array_keys($settingsFromKeyword))) {
                    $form->remove($name);
                }
            }
        }

        $form->setData($settings);

        if ($form->handleRequest($request)->isValid()) {
            $messageType = 'success';
            try {
                $manager->saveSettings($namespace, $form->getData());
                $message = $this->getTranslator()->trans('sylius.settings.update', array(), 'flashes');
            } catch (ValidatorException $exception) {
                $message = $this->getTranslator()->trans($exception->getMessage(), array(), 'validators');
                $messageType = 'error';
            }

            $this->addFlash($messageType, $message);

            /*if ($request->headers->has('referer')) {
                return $this->redirect($request->headers->get('referer'));
            }*/
        }

        return $this->render(
            'ChamiloSettingsBundle:Settings:default.html.twig',
            array(
                'settings' => $settings,
                'form' => $form->createView(),
                'keyword' => $keyword,
                'search_form' => $searchForm->createView(),
            )
        );
    }

    /**
     * @return SettingsManager
     */
    protected function getSettingsManager()
    {
        return $this->get('chamilo.settings.manager');
    }
}
