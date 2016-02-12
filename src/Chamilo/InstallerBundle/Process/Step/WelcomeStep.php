<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\InstallerBundle\Process\Step;

use Sylius\Bundle\FlowBundle\Process\Context\ProcessContextInterface;
use Sylius\Bundle\FlowBundle\Process\Step\AbstractControllerStep;

/**
 * Class WelcomeStep
 * @package Chamilo\InstallerBundle\Process\Step
 */
class WelcomeStep extends AbstractStep
{
    /**
     * {@inheritdoc}
     */
    public function displayAction(ProcessContextInterface $context)
    {
        return $this->render(
            'ChamiloInstallerBundle:Process/Step:welcome.html.twig',
            [
                'is_upgrade' => $this->isUpgrade(),
                'scenario' => $this->getScenario()
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function forwardAction(ProcessContextInterface $context)
    {
        return $this->complete();
    }
}
