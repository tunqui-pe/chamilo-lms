<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\SettingsBundle\Manager;

use Chamilo\CoreBundle\Entity\AccessUrl;
use Chamilo\CoreBundle\Entity\Course;
use Sylius\Bundle\SettingsBundle\Event\SettingsEvent;
use Doctrine\Common\Cache\Cache;
use Doctrine\Common\Persistence\ObjectManager;
use Sylius\Bundle\SettingsBundle\Manager\SettingsManagerInterface;
use Sylius\Bundle\SettingsBundle\Model\Settings;
use Sylius\Bundle\SettingsBundle\Model\SettingsInterface;
use Sylius\Bundle\SettingsBundle\Schema\SchemaRegistryInterface;
use Sylius\Bundle\SettingsBundle\Schema\SettingsBuilder;
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
class SettingsManager implements SettingsManagerInterface
{
    private $url;
    /**
     * @var ServiceRegistryInterface
     */
    private $schemaRegistry;

    /**
     * @var ServiceRegistryInterface
     */
    private $resolverRegistry;

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

    /**
     * @param ServiceRegistryInterface $schemaRegistry
     * @param ServiceRegistryInterface $resolverRegistry
     * @param ObjectManager $manager
     * @param FactoryInterface $settingsFactory
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        $schemaRegistry,
        $resolverRegistry,
        ObjectManager $manager,
         $settingsFactory,
        $eventDispatcher
    ) {
        $this->schemaRegistry = $schemaRegistry;
        $this->resolverRegistry = $resolverRegistry;
        $this->manager = $manager;
        $this->settingsFactory = $settingsFactory;
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
            $this->save($schema, $settings);
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
        $settings = $this->load($namespace);

        return $settings->get($name);
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

        $schemaAlias = 'chamilo_core.settings.'.$schemaAlias;

        $settings = $this->settingsFactory->createNew();
        //$settings->setSchemaAlias($schemaAlias);

        $parameters = $this->getParameters($schemaAlias);
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
        $schema = $this->schemaRegistry->get($settings->getSchemaAlias());
        //$schema = $this->schemaRegistry->getSchema($namespace);

        $settingsBuilder = new SettingsBuilder();
        $schema->buildSettings($settingsBuilder);

        $parameters = $settingsBuilder->resolve($settings->getParameters());

        foreach ($settingsBuilder->getTransformers() as $parameter => $transformer) {
            if (array_key_exists($parameter, $parameters)) {
                $parameters[$parameter] = $transformer->transform($parameters[$parameter]);
            }
        }

        if (isset($this->resolvedSettings[$namespace])) {
            $this->resolvedSettings[$namespace]->setParameters($parameters);
        }

        $persistedParameters = $this->parameterRepository->findBy(
            array('category' => $namespace)
        );

        $persistedParametersMap = array();

        foreach ($persistedParameters as $parameter) {
            $persistedParametersMap[$parameter->getName()] = $parameter;
        }

        $event = $this->eventDispatcher->dispatch(
            SettingsEvent::PRE_SAVE,
            new SettingsEvent($namespace, $settings, $parameters)
        );

        $url = $event->getArgument('url');

        foreach ($parameters as $name => $value) {
            if (isset($persistedParametersMap[$name])) {
                if ($value instanceof Course) {
                    $value = $value->getId();
                }
                $persistedParametersMap[$name]->setValue($value);
            } else {
                /** @var SettingsCurrent $parameter */
                $parameter = $this->parameterFactory->createNew();

                $parameter
                    ->setNamespace($namespace)
                    ->setName($name)
                    ->setValue($value)
                    ->setUrl($url)
                    ->setAccessUrl(1)
                    ->setAccessUrlLocked(0)
                    ->setAccessUrlChangeable(1)
                ;

                /* @var $errors ConstraintViolationListInterface */
                $errors = $this->validator->validate($parameter);
                if (0 < $errors->count()) {
                    throw new ValidatorException($errors->get(0)->getMessage());
                }

                $this->parameterManager->persist($parameter);
            }
        }

        $this->parameterManager->flush();

        $this->eventDispatcher->dispatch(
            SettingsEvent::POST_SAVE,
            new SettingsEvent($namespace, $settings, $parameters)
        );

        $this->cache->save($namespace, $parameters);
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
