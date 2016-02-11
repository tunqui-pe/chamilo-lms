<?php

namespace Chamilo\InstallerBundle\Process;

use Sylius\Bundle\FlowBundle\Process\Builder\ProcessBuilderInterface;
use Sylius\Bundle\FlowBundle\Process\Scenario\ProcessScenarioInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class InstallScenario extends ContainerAware implements ProcessScenarioInterface
{
    /**
     * {@inheritdoc}
     */
    public function build(ProcessBuilderInterface $builder)
    {
        $builder
            ->add('welcome', new Step\WelcomeStep())
            ->add('configure', new Step\ConfigureStep())
            ->add('schema', new Step\SchemaStep())
            ->add('setup', new Step\SetupStep())
            ->add('installation', new Step\InstallationStep())
            ->add('final', new Step\FinalStep())
            ->setRedirect('homepage')
        ;
    }
}
