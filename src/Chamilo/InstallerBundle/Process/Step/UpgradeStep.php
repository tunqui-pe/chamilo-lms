<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\InstallerBundle\Process\Step;

use Sylius\Bundle\FlowBundle\Process\Context\ProcessContextInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Chamilo\InstallerBundle\InstallerEvents;
use Chamilo\InstallerBundle\CommandExecutor;
use Chamilo\InstallerBundle\ScriptExecutor;

/**
 * Class UpgradeStep
 * @package Chamilo\InstallerBundle\Process\Step
 */
class UpgradeStep extends AbstractStep
{
    /**
     * @param ProcessContextInterface $context
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function displayAction(ProcessContextInterface $context)
    {
        set_time_limit(900);
        $request = $context->getRequest();
        $action = $request->query->get('action');

        switch ($action) {
            case 'upgrade':
                // Means it comes from chamilo 2.x
                return $this->handleAjaxAction(
                    'chamilo:platform:update',
                    array('--force')
                );
                break;
            case 'pages':
                $this->handleAjaxAction(
                    'sonata:page:update-core-routes',
                    array('--site' => array('all'))
                );
                return $this->handleAjaxAction(
                    'sonata:page:create-snapshots',
                    array('--site' => array('all'))
                );
            case 'settings':
                $settingsManager = $this->container->get(
                    'chamilo.settings.manager'
                );
                $url = $this->container->get('doctrine')->getRepository('ChamiloCoreBundle:AccessUrl')->find(1);
                $settingsManager->installSchemas($url);
                return new JsonResponse(array('result' => true, 'exitCode' => 0));
                break;
            case 'assets':
                /*return $this->handleAjaxAction(
                    'oro:assets:install',
                    array('target' => './', '--exclude' => ['OroInstallerBundle'])
                );*/
                return $this->handleAjaxAction(
                    'assets:install',
                    array(
                        'target' => './',
                        '--symlink' => true,
                        '--relative' => true,
                    )
                );
            case 'assetic':
                return $this->handleAjaxAction('assetic:dump');
            case 'finish':
                $this->get('event_dispatcher')->dispatch(
                    InstallerEvents::FINISH
                );
                // everything was fine - update installed flag in parameters.yml
                $dumper = $this->get('chamilo_installer.yaml_persister');
                $params = $dumper->parse();
                $params['system']['installed'] = date('c');
                $dumper->dump($params);
                // launch 'cache:clear' to set installed flag in DI container
                // suppress warning: ini_set(): A session is active. You cannot change the session
                // module's ini settings at this time
                error_reporting(E_ALL ^ E_WARNING);

                return $this->handleAjaxAction(
                    'cache:clear',
                    array('--env' => 'prod', '--no-debug' => true)
                );
        }

        // check if we have package installation step
        /*if (strpos($action, 'installerScript-') !== false) {
            $scriptFile = $this->container->get(
                'chamilo_installer.script_manager'
            )->getScriptFileByKey(
                str_replace('installerScript-', '', $action)
            );

            $scriptExecutor = new ScriptExecutor(
                $this->getOutput(),
                $this->container,
                new CommandExecutor(
                    $this->container->getParameter('kernel.environment'),
                    $this->getOutput(),
                    $this->getApplication()
                )
            );
            $scriptExecutor->runScript($scriptFile);

            return new JsonResponse(array('result' => true));
        }*/

        $scriptManager = $this->container->get('chamilo_installer.script_manager');

        return $this->render(
            'ChamiloInstallerBundle:Process/Step:installation.html.twig',
            array(
                'is_upgrade' => $this->isUpgrade(),
                'scenario' => $this->getScenario(),
                'loadFixtures' => false,
                'installerScripts' => $scriptManager->getScriptLabels(),
            )
        );
    }
}
