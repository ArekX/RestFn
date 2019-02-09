<?php
/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

namespace ArekX\JsonQL\Config;


use Auryn\ConfigException;
use Auryn\InjectionException;
use Auryn\Injector;

/**
 * Interface ConfigInterface Interface for all configuration classes.
 * @package ArekX\JsonQL\Config
 */
interface ConfigInterface
{
    /**
     * ConfigInterface constructor.
     * @param array $config Configuration used.
     * @param array $params Parameters used.
     */
    public function __construct(array $config = [], array $params = []);

    /**
     * Returns current DI container implementing Injector.
     */
    public function getDI(): Injector;

    /**
     * Returns core configuration.
     */
    public function getInitialConfig(): array;

    /**
     * Returns current passed configuration.
     *
     * @return array
     */
    public function getConfig(): array;

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
    public function getConfigItem($name, $default = null);


    /**
     * Returns all passed params.
     *
     * @return array
     */
    public function getParams(): array;


    /**
     * Instantiate/provision a class instance
     *
     * @param string $name
     * @param array $args
     * @throws InjectionException if a cyclic gets detected when provisioning
     * @throws \Auryn\InjectionException
     * @return mixed
     */
    public function make($name, array $parameters = []);

    /**
     * Share the specified class/instance across the Injector context
     *
     * @param mixed $nameOrInstance The class or object to share
     * @throws ConfigException if $nameOrInstance is not a string or an object
     */
    public function share($nameOrInstance);


    /**
     * Returns one parameter from params. Name can be accessed recursively using dot notation.
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
     * @param $name string Name of the parameter to be retrieved, can be dot notation.
     * @param $default mixed Default value to be returned if parameter is missing.
     * @return mixed
     */
    public function getParam(string $name, $default = null);
}