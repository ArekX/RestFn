<?php
/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

namespace ArekX\JsonQL\Helpers;

use ArekX\JsonQL\Config\Config;
use Auryn\ConfigException;

/**
 * Class DI Helper for Dependency Injection
 *
 * This helper has functions to instantiate the application as well as handle standardized
 * wiring procedures present in this app.
 *
 * Also this DI helper should be used to instantiate all new instances of a class since that
 * way they can be auto-wired differently which makes the code more testable and maintainable.
 *
 * @package ArekX\JsonQL\Helpers
 */
class DI
{
    /**
     * Configuration used for the DI.
     * @var Config
     */
    protected static $config;

    /**
     * Bootstraps the current configuration
     *
     * This function is used to start the application configured using the configuration.
     *
     * Passed configuration will be used for all further `make()` functions.
     *
     * @see DI::make()
     * @param Config $config Configuration which will be used.
     */
    public static function bootstrap(Config $config)
    {
        static::$config = $config;

        $config->bootstrap();
    }

    /**
     * Makes new instance of a class in a DI way.
     *
     * @param string $name Name of the parameter or class name.
     * @param array $params Parameters which will be passed.
     * @return mixed Instance created.
     *
     * @throws \Auryn\InjectionException
     */
    public static function make($name, $params = [])
    {
        return static::$config->make($name, $params);
    }


    /**
     * Share the specified class/instance across the Injector context
     *
     * @param mixed $nameOrInstance The class or object to share
     * @throws ConfigException if $nameOrInstance is not a string or an object
     * @throws \Auryn\ConfigException
     */
    public static function share($nameOrInstance)
    {
        static::$config->share($nameOrInstance);
    }
}