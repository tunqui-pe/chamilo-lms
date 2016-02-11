<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\InstallerBundle\Process\Step;

use Sylius\Bundle\FlowBundle\Process\Context\ProcessContextInterface;

/**
 * Class ConfigureStep
 * @package Chamilo\InstallerBundle\Process\Step
 */
class ConfigureStep extends AbstractStep
{
    /**
     * @param ProcessContextInterface $context
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function displayAction(ProcessContextInterface $context)
    {
        $options = [];
        if ($this->container->hasParameter('installed') &&
            $this->container->getParameter('installed')
        ) {
            $options['disabled'] = true;
            //return $this->redirect($this->generateUrl('home'));
        }

        $form = $this->createConfigurationForm($options);

        return $this->render(
            'ChamiloInstallerBundle:Process/Step:configure.html.twig',
            array(
                'form' => $form->createView(),
            )
        );
    }

    /**
     * @param ProcessContextInterface $context
     * @return \Sylius\Bundle\FlowBundle\Process\Step\ActionResult|\Symfony\Component\HttpFoundation\Response
     */
    public function forwardAction(ProcessContextInterface $context)
    {
        set_time_limit(600);
        $form = $this->createConfigurationForm();
        $request = $context->getRequest();

        $form->handleRequest($request);
        //var_dump($context->getRequest()->get('chamilo_installer_configuration'));
        if ($form->isValid()) {
            $data = $form->getData();
            $context->getStorage()->set(
                'fullDatabase',
                $form->has('database') && $form->get('database')->has('chamilo_installer_database_drop_full') &&
                $form->get('database')->get('chamilo_installer_database_drop_full')->getData()
            );

            $this->get('chamilo_installer.yaml_persister')->dump($data);

            return $this->complete();
        }

        return $this->render(
            'ChamiloInstallerBundle:Process/Step:configure.html.twig',
            array(
                'form' => $form->createView(),
            )
        );
    }

    /**
     * @param array $options
     * @return \Symfony\Component\Form\Form
     */
    protected function createConfigurationForm($options = array())
    {
        $data = $this->get('chamilo_installer.yaml_persister')->parse();

        return $this->createForm(
            'chamilo_installer_configuration',
            empty($data) ? null : $data,
            $options
        );
    }
}
