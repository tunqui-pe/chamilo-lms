<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\InstallerBundle\Process\Step;

use Chamilo\InstallerBundle\Form\Type\ConfigurationType;
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
        $upgrade = false;
        if ($this->isCommonUpgrade()) {
            $options['disabled'] = true;
            $upgrade = true;
            //return $this->redirect($this->generateUrl('home'));
        }

        $form = $this->createConfigurationForm($options, $upgrade);

        return $this->render(
            'ChamiloInstallerBundle:Process/Step:configure.html.twig',
            array(
                'form' => $form->createView(),
                'is_upgrade' => $upgrade,
                'scenario' => $this->getScenario()
            )
        );
    }

    /**
     * @param ProcessContextInterface $context
     * @return \Sylius\Bundle\FlowBundle\Process\Step\ActionResult|\Symfony\Component\HttpFoundation\Response
     */
    public function forwardAction(ProcessContextInterface $context)
    {
        //set_time_limit(600);
        $form = $this->createConfigurationForm();
        $request = $context->getRequest();
        $upgrade = $this->isCommonUpgrade();

        if ($upgrade) {
            return $this->complete();
        }

        $form->handleRequest($request);

        if ($form->isValid()) {

            $data = $form->getData();
            $context->getStorage()->set(
                'fullDatabase',
                $form->has('database') && $form->get('database')->has('chamilo_installer_database_drop_full') &&
                $form->get('database')->get('chamilo_installer_database_drop_full')->getData()
            );

            $this->get('chamilo_installer.env_persister')->dump($data);
            //$this->get('chamilo_installer.yaml_persister')->dump($data);

            return $this->complete();
        }

        return $this->render(
            'ChamiloInstallerBundle:Process/Step:configure.html.twig',
            array(
                'form' => $form->createView(),
                'is_upgrade' => $upgrade,
                'scenario' => $this->getScenario(),
            )
        );
    }

    /**
     * @param array $options
     * @return \Symfony\Component\Form\Form
     */
    protected function createConfigurationForm($options = array(), $upgrade = false)
    {
        $this->get('chamilo_installer.env_persister')->parse();
        $data['is_upgrade'] = $upgrade;

        return $this->createForm(
            ConfigurationType::class,
            empty($data) ? null : $data,
            $options
        );
    }
}
