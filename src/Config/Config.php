<?php
/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

namespace ArekX\JsonQL\Config;


use ArekX\JsonQL\Helpers\DI;
use ArekX\JsonQL\Helpers\Value;
use ArekX\JsonQL\MainApplication;
use Auryn\InjectionException;
use Auryn\Injector;

/**
 * Class Config Contains all of the configuration for the application.
 *
 * Configuration is separated into segments:
 * ```php
 * [
 *    'app' => [] // Application class DI configuration.
 *    'di' => [] // Dependency injection container configuration.
 *    'services' => [] // App services configuration
 *    'core' => [] // Core services configuration
 * ]
 * ```
 *
 * @package ArekX\JsonQL\Config
 */
abstract class Config implements ConfigInterface
{
    const APP = 'app';
    const DI = 'di';
    const SERVICES = 'services';
    const CORE = 'core';

    /** @var array Input configuration array. */
    protected $config;

    /** @var array Input parameter array. */
    protected $params;

    /** @var Injector Dependency injector */
    protected $injector;

    /**
     * Config constructor.
     * @param array $config Configuration array for classes.
     * @param array $params Configuration parameters for the app.
     * @throws \Exception
     */
    public function __construct(array $config = [], array $params = [])
    {
        $this->config = Value::merge($this->getInitialConfig(), $config);
        $this->params = $params;
        $this->injector = $this->createDI();
    }


    /**
     * Returns core configuration.
     *
     * @return array
     */
    public function getInitialConfig(): array
    {
        return [
            self::APP => $this->getAppConfig(),
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
     *          'sub' => 'value'
     *       ]
     *    ]
     * ]
     *
     * Can be accessed as: param.name.sub.
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
     * @return Injector
     */
    public function getDI(): Injector
    {
        return $this->injector;
    }

    /**
     * Returns one config value from configuration. Name can be accessed recursively using dot notation.
     *
     * Example:
     * ```php
     * [
     *    'param' => [
     *       'name' => [
     *          'sub' => 'value'
     *       ]
     *    ]
     * ]
     * ```
     *
     * Can be accessed as: `param.name.sub`
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
     * @return Injector Created dependency injector
     * @throws \Exception
     */
    protected function createDI(): Injector
    {
        $injector = new Injector();

        $this->applyServiceConfig(self::SERVICES, $injector);
        $this->applyServiceConfig(self::CORE, $injector);

        $injector->alias(MainApplication::class, $this->getApplicationClass());
        $injector->define($this->getApplicationClass(), [':setup' => $this->config[self::APP]]);
        $injector->share($this->getApplicationClass());

        $injector->share($this);
        $injector->alias(ConfigInterface::class, static::class);

        return $injector;
    }


    /**
     * Bootstrap the main application.
     */
    public function bootstrap()
    {
        $this->make(MainApplication::class)->run();
    }

    /**
     * @inheritdoc
     */
    public function make($name, array $parameters = [])
    {
        return $this->injector->make($name, $parameters);
    }


    /**
     * @inheritdoc
     */
    public function share($nameOrInstance)
    {
        $this->injector->share($nameOrInstance);
    }

    /**
     * Returns application class used to bootstrap the application and configure it.
     * @return string
     */
    protected abstract function getApplicationClass(): string;

    /**
     * Returns initial main application config.
     * @return mixed
     */
    protected function getAppConfig()
    {
        return [];
    }

    protected function applyServiceConfig(string $type, Injector $injector): void
    {
        foreach ($this->config[$type] as $class => $config) {

            $injector->share($class);

            if (is_array($config)) {
                if (!empty($config['@class'])) {
                    $injector->alias($class, $config['@class']);
                    unset($config['@class']);
                }

                $injector->define($class, [':setup' => $config]);
                continue;
            }

            $injector->alias($class, $config);
        }
    }
}