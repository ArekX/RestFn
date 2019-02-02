<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace tests\Config;


use ArekX\JsonQL\Config\Config;
use ArekX\JsonQL\Config\ConfigInterface;
use tests\Mock\MockConfig;

trait ConstructorFunction
{
    public function testConfigGetsResolvedOnce()
    {
        $config = $this->di->get(ConfigInterface::class);
        $this->assertEquals($config, $this->config);
    }

    public function testEmptyConfig()
    {
        $config = new TestConfig();

        $this->assertEquals([], $config->getConfigItem(Config::SERVICES));
        $this->assertEquals([], $config->getConfigItem(Config::CORE));
        $this->assertEmpty($config->getParams());
    }

    public function testServicesIsFilled()
    {
        $config = new MockConfig([
            Config::SERVICES => [
                'name' => 'value'
            ]
        ]);

        $this->assertEquals(['name' => 'value'], $config->getConfigItem(Config::SERVICES));
    }
}