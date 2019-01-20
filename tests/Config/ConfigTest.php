<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace tests\Config;

use ArekX\JsonQL\Rest\Config;
use Psr\Container\ContainerInterface;
use tests\TestCase;

class ConfigTest extends TestCase
{
    use GetParamFunction, ConstructorFunction, GetConfigsFunction;

    public function testGetDI()
    {
        $config = new Config();
        $this->assertInstanceOf(ContainerInterface::class, $config->getDI());
        $this->assertNotEquals($config->getDI(), $this->di);
    }
}