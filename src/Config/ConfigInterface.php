<?php
/**
  * @author Aleksandar Panic
  * @link https://jsonql.readthedocs.io/
  * @license: http://www.apache.org/licenses/LICENSE-2.0
  * @since 1.0.0
 **/

namespace ArekX\JsonQL\Config;


use Psr\Container\ContainerInterface;

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
     * Returns current DI container implementing ContainerInterface.
     */
    public function getDI(): ContainerInterface;

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
     *          'subname' => 'value'
     *       ]
     *    ]
     * ]
     * ```
     *
     * Can be accessed as: param.name.subname.
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
     * Returns one parameter from params. Name can be accessed recursively using dot notation.
     *
     * Example:
     * ```php
     * [
     *    'param' => [
     *       'name' => [
     *          'subname' => 'value'
     *       ]
     *    ]
     * ]
     * ```
     *
     * Can be accessed as: param.name.subname.
     *
     * @param $name string Name of the parameter to be retrieved, can be dot notation.
     * @param $default mixed Default value to be returned if parameter is missing.
     * @return mixed
     */
    public function getParam(string $name, $default = null);
}