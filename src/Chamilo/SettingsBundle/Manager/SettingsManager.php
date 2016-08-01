<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\SettingsBundle\Manager;

use Chamilo\CoreBundle\Entity\AccessUrl;
use Chamilo\CoreBundle\Entity\Course;
use Chamilo\CoreBundle\Settings\PlatformSettingsSchema;
use Sylius\Bundle\ResourceBundle\Controller\EventDispatcherInterface;
use Sylius\Bundle\SettingsBundle\Event\SettingsEvent;
use Doctrine\Common\Cache\Cache;
use Doctrine\Common\Persistence\ObjectManager;
use Sylius\Bundle\SettingsBundle\Manager\SettingsManagerInterface;
use Sylius\Bundle\SettingsBundle\Model\Settings;
use Sylius\Bundle\SettingsBundle\Model\SettingsInterface;
use Sylius\Bundle\SettingsBundle\Schema\SchemaInterface;
use Sylius\Bundle\SettingsBundle\Schema\SchemaRegistryInterface;
use Sylius\Bundle\SettingsBundle\Schema\SettingsBuilder;
use Sylius\Component\Registry\ServiceRegistryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Component\Validator\ValidatorInterface;
use Sylius\Bundle\SettingsBundle\Manager\SettingsManager as SyliusSettingsManager;
use Chamilo\CoreBundle\Entity\SettingsCurrent;

/**
 * Class SettingsManager
 * @package Chamilo\SettingsBundle\Manager
 */
class SettingsManager
{
    private $url;

    /**
     * @var ServiceRegistryInterface
     */
    private $schemaRegistry;

    /**
     * @var ObjectManager
     */
    private $manager;

    /**
     * @var FactoryInterface
     */
    private $settingsFactory;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;


    public function __construct(
        SchemaRegistryInterface $schemaRegistry,
        ObjectManager $parameterManager,
        RepositoryInterface $parameterRepository,
        Cache $cache,
        ValidatorInterface $validator,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->schemaRegistry = $schemaRegistry;
        $this->parameterManager = $parameterManager;
        $this->parameterRepository = $parameterRepository;
        $this->cache = $cache;
        $this->validator = $validator;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @return AccessUrl
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param AccessUrl $url
     */
    public function setUrl(AccessUrl $url)
    {
        $this->url = $url;
    }

    /**
     * @param AccessUrl $url
     */
    public function installSchemas(AccessUrl $url)
    {
        $this->url = $url;
        $schemas = $this->getSchemas();
        $schemas = array_keys($schemas);

        /**
         * @var string $key
         * @var \Sylius\Bundle\SettingsBundle\Schema\SchemaInterface $schema
         */
        foreach ($schemas as $schema) {
            $settings = $this->load($schema);
            $this->save($settings);
        }
    }

    /**
     * @return array
     */
    public function getSchemas()
    {
        return $this->schemaRegistry->all();
    }

    /**
     * @param $name
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public function getSetting($name)
    {
        if (false === strpos($name, '.')) {
            throw new \InvalidArgumentException(sprintf('Parameter must be in format "namespace.name", "%s" given.', $name));
        }

        list($namespace, $name) = explode('.', $name);
        $settings = $this->load($this->convertNameSpaceToService($namespace));

        return $settings->get($name);
    }

    public function convertNameSpaceToService($namespace)
    {
        return 'chamilo_core.settings.'.$namespace;
    }

    /**
     * {@inheritdoc}
     */
    public function load($schemaAlias, $namespace = null, $ignoreUnknown = true)
    {
        /*$schema = $this->schemaRegistry->get($schemaAlias);
        $resolver = $this->resolverRegistry->get($schemaAlias);

        // try to resolve settings for schema alias and namespace
        $settings = $resolver->resolve($schemaAlias, $namespace);

dump($settings);

        $parameters = $this->getParameters($namespace);

        $schema = $this->schemaRegistry->get($namespace);

        $settingsBuilder = new SettingsBuilder();
        $schema->buildSettings($settingsBuilder);

        foreach ($settingsBuilder->getTransformers() as $parameter => $transformer) {
            if (array_key_exists($parameter, $parameters)) {
                $parameters[$parameter] = $transformer->reverseTransform($parameters[$parameter]);
            }
        }
        $parameters = $settingsBuilder->resolve($parameters);

        return $this->resolvedSettings[$namespace] = new Settings($parameters);*/

        /*$schema = $this->schemaRegistry->get($schemaAlias);
        $resolver = $this->resolverRegistry->get($schemaAlias);

        // try to resolve settings for schema alias and namespace
        $settings = $resolver->resolve($schemaAlias, $namespace);

        if (!$settings) {
            $settings = $this->settingsFactory->createNew();
            $settings->setSchemaAlias($schemaAlias);
        }*/

        //$schemaAlias = 'chamilo_core.settings.'.$schemaAlias;

        $settings = $this->settingsFactory->createNew();
        $settings->setSchemaAlias($schemaAlias);

        $parameters = $this->getParameters($schemaAlias);

        /** @var SchemaInterface $schema */
        $schema = $this->schemaRegistry->get($schemaAlias);

        // We need to get a plain parameters array since we use the options resolver on it
        //$parameters = $settings->getParameters();

        $settingsBuilder = new SettingsBuilder();
        $schema->buildSettings($settingsBuilder);

        // Remove unknown settings' parameters (e.g. From a previous version of the settings schema)
        if (true === $ignoreUnknown) {
            foreach ($parameters as $name => $value) {
                if (!$settingsBuilder->isDefined($name)) {
                    unset($parameters[$name]);
                }
            }
        }

        $parameters = $settingsBuilder->resolve($parameters);
        $settings->setParameters($parameters);

        return $settings;
    }

    /**
     * {@inheritdoc}
     * @throws ValidatorException
     */
    public function save(SettingsInterface $settings)
    {
        $schemaAlias = $settings->getSchemaAlias();

        $schemaAliasChamilo = str_replace('chamilo_core.settings.', '', $schemaAlias);

        $schema = $this->schemaRegistry->get($schemaAlias);

        $settingsBuilder = new SettingsBuilder();
        $schema->buildSettings($settingsBuilder);

        $parameters = $settingsBuilder->resolve($settings->getParameters());

        foreach ($settingsBuilder->getTransformers() as $parameter => $transformer) {
            if (array_key_exists($parameter, $parameters)) {
                $parameters[$parameter] = $transformer->transform($parameters[$parameter]);
            }
        }

        /** @var \Sylius\Bundle\SettingsBundle\Event\SettingsEvent $event */
        $event = $this->eventDispatcher->dispatch(
            SettingsEvent::PRE_SAVE,
            new SettingsEvent($settings)
        );

        /** @var \Chamilo\CoreBundle\Entity\SettingsCurrent $url */
        $url = $event->getSettings()->getAccessUrl();

        foreach ($parameters as $name => $value) {
            if (isset($persistedParametersMap[$name])) {
                if ($value instanceof Course) {
                    $value = $value->getId();
                }
                $persistedParametersMap[$name]->setValue($value);
            } else {
                /** @var SettingsCurrent $setting */
                $setting = $this->settingsFactory->createNew();
                $setting->setSchemaAlias($schemaAlias);

                $setting
                    ->setNamespace($schemaAliasChamilo)
                    ->setName($name)
                    ->setValue($value)
                    ->setUrl($url)
                    ->setAccessUrlLocked(0)
                    ->setAccessUrlChangeable(1)
                ;


                /* @var $errors ConstraintViolationListInterface */
                /*$errors = $this->->validate($parameter);
                if (0 < $errors->count()) {
                    throw new ValidatorException($errors->get(0)->getMessage());
                }*/
                $this->manager->persist($setting);
                $this->manager->flush();
            }
        }
        /*$parameters = $settingsBuilder->resolve($settings->getParameters());
        $settings->setParameters($parameters);

        $this->eventDispatcher->dispatch(SettingsEvent::PRE_SAVE, new SettingsEvent($settings));

        $this->manager->persist($settings);
        $this->manager->flush();

        $this->eventDispatcher->dispatch(SettingsEvent::POST_SAVE, new SettingsEvent($settings));*/
    }

    /**
     * Load parameter from database.
     *
     * @param string $namespace
     *
     * @return array
     */
    private function getParameters($namespace)
    {
        $repo = $this->manager->getRepository('ChamiloCoreBundle:SettingsCurrent');
        $parameters = [];
        foreach ($repo->findBy(array('category' => $namespace)) as $parameter) {
            $parameters[$parameter->getName()] = $parameter->getValue();
        }

        return $parameters;
    }

    public function getParametersFromKeyword($namespace, $keyword = '')
    {
        $criteria = array('category' => $namespace);
        if (!empty($keyword)) {
            $criteria['variable'] = $keyword;
        }

        $parametersFromDb = $this->parameterRepository->findBy($criteria);

        $parameters = array();
        /** @var \Chamilo\CoreBundle\Entity\SettingsCurrent $parameter */
        foreach ($parametersFromDb as $parameter) {
            $parameters[$parameter->getName()] = $parameter->getValue();
        }

        return $parameters;
    }
}
