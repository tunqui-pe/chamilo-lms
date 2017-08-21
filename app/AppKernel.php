<?php
/* For licensing terms, see /license.txt */

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;
use Chamilo\CoreBundle\Component\Utils\ChamiloApi;

/**
 * Class AppKernel
 */
class AppKernel extends Kernel
{
    protected $rootDir;

    /**
     * @return array
     */
    public function registerBundles()
    {
        $bundles = array(
            //new Doctrine\Bundle\PHPCRBundle\DoctrinePHPCRBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle($this),

            // KNP HELPER BUNDLES
                                    // Data grid
            new APY\DataGridBundle\APYDataGridBundle(),
            new A2lix\TranslationFormBundle\A2lixTranslationFormBundle(),

            //new SimpleThings\EntityAudit\SimpleThingsEntityAuditBundle(),

            // CMF Integration
            new Symfony\Cmf\Bundle\RoutingBundle\CmfRoutingBundle(),
            //new Symfony\Cmf\Bundle\SearchBundle\CmfSearchBundle(),
            //new Symfony\Cmf\Bundle\BlogBundle\CmfBlogBundle(),
            //new Sonata\DoctrinePHPCRAdminBundle\SonataDoctrinePHPCRAdminBundle(),

            // Oauth
            //new Gregwar\CaptchaBundle\GregwarCaptchaBundle(),


            // Sylius
            new Sylius\Bundle\SettingsBundle\SyliusSettingsBundle(),
            //new Sylius\Bundle\AttributeBundle\SyliusAttributeBundle(),

            new Bazinga\Bundle\HateoasBundle\BazingaHateoasBundle(),
            new winzou\Bundle\StateMachineBundle\winzouStateMachineBundle(),

            // Chamilo

            //new Chamilo\TimelineBundle\ChamiloTimelineBundle()

            // Based in Sonata

            /*
            // Chamilo course tool
            //new Chamilo\NotebookBundle\ChamiloNotebookBundle(),
            */
            // Data
            // Extra
            /*
            //new JMS\TranslationBundle\JMSTranslationBundle(),
            //new JMS\DiExtraBundle\JMSDiExtraBundle($this),
            //new JMS\AopBundle\JMSAopBundle(),
            /*new Bazinga\Bundle\FakerBundle\BazingaFakerBundle(),
            //new Chamilo\CmsBundle\ChamiloCmsBundle(),
             */
        );

        return $bundles;
    }

    /**
     * @param LoaderInterface $loader
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir().'/config/config_'.$this->getEnvironment().'.yml');
    }

    /**
     * @return string
     */
    public function getRootDir()
    {
        if (null === $this->rootDir) {
            $r = new \ReflectionObject($this);
            $this->rootDir = str_replace('\\', '/', dirname($r->getFileName()));
        }

        return $this->rootDir;
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
        return $this->getRealRootDir().'app/config/';
    }

    /**
     * @return string
     */
    public function getConfigurationFile()
    {
        return $this->getRealRootDir().'app/config/configuration.php';
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

    public function getCacheDir()
    {
        return dirname(__DIR__).'/var/cache/'.$this->environment;
    }

    public function getLogDir()
    {
        return dirname(__DIR__).'/var/logs/'.$this->environment;
    }
}

