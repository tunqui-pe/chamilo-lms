<?php
/* For licensing terms, see /license.txt */

namespace Chamilo;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\RouteCollectionBuilder;
use Chamilo\CoreBundle\Component\Utils\ChamiloApi;

/**
 * @author Fabien Potencier <fabien@symfony.com>
 */
final class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    private const CONFIG_EXTS = '.{php,xml,yaml,yml}';

    /**
     * @return string
     */
    public function getCacheDir(): string
    {
        return dirname(__DIR__).'/var/cache/'.$this->environment;
    }

    /**
     * @return string
     */
    public function getLogDir(): string
    {
        return dirname(__DIR__).'/var/logs';
    }

    /**
     * @return iterable
     */
    public function registerBundles(): iterable
    {
        $contents = require dirname(__DIR__).'/config/bundles.php';
        foreach ($contents as $class => $envs) {
            if (isset($envs['all']) || isset($envs[$this->environment])) {
                yield new $class();
            }
        }
    }

    /**
     * @param ContainerBuilder $container
     * @param LoaderInterface $loader
     */
    protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader): void
    {
        $confDir = dirname(__DIR__).'/config';
        $loader->load($confDir.'/packages/*'.self::CONFIG_EXTS, 'glob');
        if (is_dir($confDir.'/packages/'.$this->environment)) {
            $loader->load($confDir.'/packages/'.$this->environment.'/**/*'.self::CONFIG_EXTS, 'glob');
        }
        $loader->load($confDir.'/container'.self::CONFIG_EXTS, 'glob');
    }

    protected function configureRoutes(RouteCollectionBuilder $routes): void
    {
        $confDir = dirname(__DIR__).'/config';
        if (is_dir($confDir.'/routing/')) {
            $routes->import($confDir.'/routing/*'.self::CONFIG_EXTS, '/', 'glob');
        }
        if (is_dir($confDir.'/routing/'.$this->environment)) {
            $routes->import($confDir.'/routing/'.$this->environment.'/**/*'.self::CONFIG_EXTS, '/', 'glob');
        }
        $routes->import($confDir.'/routing'.self::CONFIG_EXTS, '/', 'glob');
    }

    /**
     * Returns the real root path
     * @return string
     */
    public function getRealRootDir()
    {
        return realpath($this->getRootDir().'/../').'/';
    }

    /**
     * Returns the data path
     * @return string
     */
    public function getDataDir()
    {
        return $this->getRealRootDir().'data/';
    }

    /**
     * @return string
     */
    public function getAppDir()
    {
        return $this->getRealRootDir().'app/';
    }

    /**
     * @return string
     */
    public function getConfigDir()
    {
        return $this->getRealRootDir().'config/';
    }

    /**
     * @return string
     */
    public function getConfigurationFile()
    {
        return $this->getRealRootDir().'config/.env';
    }

    /**
     * @param array $configuration
     */
    public function setApi(array $configuration)
    {
        new ChamiloApi($configuration);
    }

    /**
    * Check if system is installed
    * @return bool
    */
    public function isInstalled()
    {
        return !empty($this->getContainer()->getParameter('installed'));
    }
}
