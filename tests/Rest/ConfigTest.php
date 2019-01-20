<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace tests\Rest;

use ArekX\JsonQL\Rest\Application;
use ArekX\JsonQL\Rest\Config;
use ArekX\JsonQL\BaseApplication;
use tests\TestCase;

class ConfigTest extends TestCase
{
    public function testApplicationCreatedIsRestApplication()
    {
        $config = new Config();
        $this->assertInstanceOf(Application::class, $config->getDI()->get(BaseApplication::class));
    }
}