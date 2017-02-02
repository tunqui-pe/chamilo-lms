<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\InstallerBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader;

/**
 * Class ChamiloInstallerExtension
 * @package Chamilo\InstallerBundle\DependencyInjection
 */
class ChamiloInstallerExtension extends Extension
{
    public function load(array $config, ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );
        $loader->load('services.yml');
        $loader->load('form.yml');
    }
}
