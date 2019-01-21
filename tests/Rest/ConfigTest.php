<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace tests\Rest;

use ArekX\JsonQL\Rest\Application;
use ArekX\JsonQL\Rest\Config;
use ArekX\JsonQL\MainApplication;
use tests\Mock\MockApplication;
use tests\Mock\MockConfig;
use tests\TestCase;

class ConfigTest extends TestCase
{
    public function testApplicationCreatedIsRestApplication()
    {
        $config = new Config();
        $this->assertInstanceOf(Application::class, $config->getDI()->get(MainApplication::class));
    }

    public function testCallingBootstrapWillRunTheApp()
    {
        $config = new MockConfig();
        $config->bootstrap();

        /** @var MockApplication $app */
        $app = $config->getDI()->get(MainApplication::class);
        $this->assertTrue($app->ran);
    }
}