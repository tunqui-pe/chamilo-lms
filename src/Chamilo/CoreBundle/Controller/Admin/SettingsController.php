<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\CoreBundle\Controller\Admin;

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
     * @Route("/settings", name="admin_settings")
     *
     * @return array
     */
    public function indexAction()
    {
        $manager = $this->getSettingsManager();
        $schemas = $manager->getSchemas();

        return $this->render(
            '@ChamiloCore/Admin/Settings/index.html.twig',
            array(
                'schemas' => $schemas
            )
        );
    }

    /**
     * Edit configuration with given namespace.
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @Route("/settings/{namespace}", name="chamilo_platform_settings")
     *
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

        $settings = $manager->load($manager->convertNameSpaceToService($namespace));

        $form = $this->getSettingsFormFactory()->create('chamilo_core.settings.'.$namespace);

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
                $manager->save($form->getData());
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
        $schemas = $manager->getSchemas();

        return $this->render(
            '@ChamiloCore/Admin/Settings/default.html.twig',
            array(
                'schemas' => $schemas,
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
