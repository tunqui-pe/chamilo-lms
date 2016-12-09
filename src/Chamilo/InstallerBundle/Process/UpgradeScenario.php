<?php

namespace Chamilo\InstallerBundle\Process;

use Sylius\Bundle\FlowBundle\Process\Builder\ProcessBuilderInterface;
use Sylius\Bundle\FlowBundle\Process\Scenario\ProcessScenarioInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class UpgradeScenario extends ContainerAware implements ProcessScenarioInterface
{
    /**
     * {@inheritdoc}
     */
    public function build(ProcessBuilderInterface $builder)
    {
        $builder
            ->add('welcome', new Step\WelcomeStep())
            ->add('configure', new Step\ConfigureStep())
            ->add('upgrade', new Step\UpgradeStep())
            ->add('final', new Step\FinalStep())
            ->setRedirect('homepage')
        ;
    }
}
