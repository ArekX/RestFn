<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Config;


use Psr\Container\ContainerInterface;

interface ConfigInterface
{
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
    public function getParam(string $name, $default = null);
}