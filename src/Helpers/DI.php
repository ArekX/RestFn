<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Helpers;

use ArekX\JsonQL\Rest\Config;
use function DI\autowire;

class DI
{
    /** @var Config */
    public static $config;

    public static function bootstrap(Config $config)
    {
        static::$config = $config;

        $config->bootstrap();
    }

    public static function make($name, $params = [])
    {
        return static::$config->make($name, $params);
    }

    public static function wireSetup($class, $setupConfig)
    {
        return autowire($class)->constructorParameter('setup', $setupConfig);
    }

    public static function wireClass($class)
    {
        return autowire($class);
    }
}