<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Helpers;

use function DI\autowire;

class DI
{
    public static function setup($class, $setupConfig)
    {
        return autowire($class)->constructorParameter('setup', $setupConfig);
    }

    public static function class($class)
    {
        return autowire($class);
    }
}