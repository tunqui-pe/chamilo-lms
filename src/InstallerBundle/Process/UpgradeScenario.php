<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\InstallerBundle\Process;

use Sylius\Bundle\FlowBundle\Process\Builder\ProcessBuilderInterface;
use Sylius\Bundle\FlowBundle\Process\Scenario\ProcessScenarioInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * Class UpgradeScenario
 * @package Chamilo\InstallerBundle\Process
 */
class UpgradeScenario implements ProcessScenarioInterface
{
    use ContainerAwareTrait;

    /**
     * {@inheritdoc}
     */
    public function build(ProcessBuilderInterface $builder)
    {
        $checker = $this->container->get('security.authorization_checker');
        if (!$checker->isGranted('ROLE_ADMIN')) {
            return;
        }
        $builder
            ->add('welcome', new Step\WelcomeStep())
            ->add('configure', new Step\ConfigureStep())
            ->add('upgrade', new Step\UpgradeStep())
            ->add('final', new Step\FinalStep())
            ->setRedirect('homepage')
        ;
    }
}
