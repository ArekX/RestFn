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

class Config implements ConfigInterface, ContainerInterface, FactoryInterface
{
    const DI = 'di';
    const SERVICES = 'services';
    const CORE = 'core';

    /** @var array  */
    protected $config;

    /** @var array  */
    protected $params;

    /** @var Container */
    protected $container;

    public function __construct(array $config = [], array $params = [])
    {
        $this->config = Value::merge($this->getCoreConfig(), $config);
        $this->params = $params;
        $this->container = $this->createDI();
    }

    /**
     * Returns core configuration.
     */
    public function getCoreConfig(): array
    {
        return [
            self::DI => [
                'compile' => false
            ],
            self::SERVICES => [],
            self::CORE => $this->getCoreServices()
        ];
    }

    protected function getCoreServices()
    {
        return [];
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
     * Returns current DI container implementing ContainerInterface.
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
     * @return Container
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
     * Bootstrap the application.
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