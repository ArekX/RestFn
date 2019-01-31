<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Config;


use ArekX\JsonQL\Helpers\Value;
use ArekX\JsonQL\MainApplication;
use DI\Container;
use DI\ContainerBuilder;
use DI\DependencyException;
use DI\FactoryInterface;
use DI\NotFoundException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * Class Config
 * @package ArekX\JsonQL\Config
 *
 * Contains all of the configuration for the application.
 *
 * Configuration is separated into segments:
 * [
 *    'di' => [] // Dependency injection container configuration.
 *    'services' => [] // App services configuration
 *    'core' => [] // Core services configuration
 * ]
 */
class Config implements ConfigInterface, ContainerInterface, FactoryInterface
{
    const DI = 'di';
    const SERVICES = 'services';
    const CORE = 'core';

    /** @var array Input configuration array. */
    protected $config;

    /** @var array Input parameter array. */
    protected $params;

    /** @var Container DI container */
    protected $container;

    /**
     * Config constructor.
     * @param array $config Configuration array for classes.
     * @param array $params Configuration parameters for the app.
     */
    public function __construct(array $config = [], array $params = [])
    {
        $this->config = Value::merge($this->getInitialConfig(), $config);
        $this->params = $params;
        $this->container = $this->createDI();
    }


    /**
     * Returns core configuration.
     *
     * @return array
     */
    public function getInitialConfig(): array
    {
        return [
            self::DI => $this->getDIConfig(),
            self::SERVICES => $this->getServicesConfig(),
            self::CORE => $this->getCoreConfig()
        ];
    }

    /**
     * Returns initial core services configuration.
     *
     * This group contains list of services required for the app to run.
     *
     * @return array
     */
    protected function getCoreConfig(): array
    {
        return [];
    }

    /**
     * Returns initial application services configuration.
     *
     * This group contains a list of services which will be directly added to the DI.
     *
     * @return array
     */
    protected function getServicesConfig(): array
    {
        return [];
    }

    /**
     * Returns initial dependency injection configuration.
     *
     * This configuration is used to configure the DI builder.
     *
     * Available configuration:
     * [
     *    'compile' => false, // Whether or not to compile DI container for faster load.
     *    'cacheFolder' => '' // Folder destination used for caching.
     * ]
     * @return array
     */
    protected function getDIConfig(): array
    {
        return [
            'compile' => false
        ];
    }

    /**
     * Returns current passed configuration.
     *
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * Returns all passed params.
     *
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * Returns one parameter from params. Name can be accessed recursively using dot notation.
     *
     * Example:
     * [
     *    'param' => [
     *       'name' => [
     *          'subname' => 'value'
     *       ]
     *    ]
     * ]
     *
     * Can be accessed as: param.name.subname.
     *
     * @param $name string Name of the parameter to be retrieved, can be dot notation.
     * @param $default mixed Default value to be returned if parameter is missing.
     * @return mixed
     */
    public function getParam(string $name, $default = null)
    {
        return Value::get($this->params, $name, $default);
    }


    /**
     * Returns container interface used for building.
     *
     * NOTE:
     * This should be used internally for specific logic of wiring your classes in DI way.
     * Do not make this a service locator and get classes directly.
     *
     * @return ContainerInterface
     */
    public function getDI(): ContainerInterface
    {
        return $this->container;
    }

    /**
     * Returns one config value from configuration. Name can be accessed recursively using dot notation.
     *
     * Example:
     * [
     *    'param' => [
     *       'name' => [
     *          'subname' => 'value'
     *       ]
     *    ]
     * ]
     *
     * Can be accessed as: param.name.subname.
     *
     * @param $name string Name of the value to be retrieved, can be dot notation.
     * @param $default mixed Default value to be returned if parameter is missing.
     * @return mixed
     */
    public function getConfigItem($name, $default = null)
    {
        return Value::get($this->config, $name, $default);
    }


    /**
     * Creates dependency injection container with current configuration.
     *
     * @return Container Created container.
     * @throws \Exception
     */
    protected function createDI(): Container
    {
        $builder = new ContainerBuilder();

        $di = $this->config[self::DI];


        if ($di['compile']) {
            // @codeCoverageIgnoreStart
            $builder->enableCompilation($di['cacheFolder']);
            // @codeCoverageIgnoreEnd
        }

        $builder->addDefinitions($this->config[self::SERVICES]);
        $builder->addDefinitions($this->config[self::CORE]);
        $builder->addDefinitions([
            ConfigInterface::class => $this
        ]);

        return $builder->build();
    }


    /**
     * Bootstrap the main application.
     */
    public function bootstrap()
    {
        $this->get(MainApplication::class)->run();
    }

    /**
     * @inheritdoc
     */
    public function has($id)
    {
        return $this->container->has($id);
    }

    /**
     * @inheritdoc
     */
    public function get($id)
    {
        return $this->container->get($id);
    }

    /**
     * @inheritdoc
     */
    public function make($name, array $parameters = [])
    {
        return $this->container->make($name, $parameters);
    }
}