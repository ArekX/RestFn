<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace tests\Config;


use ArekX\JsonQL\Config\Config;

trait GetConfigsFunction
{
    public function testConfigIsRetrieved()
    {
        $config = new TestConfig([
            'test' => [
                'value' => 1
            ]
        ]);

        $this->assertEquals([
            Config::APP => null,
            Config::DI => [
                'compile' => false
            ],
            Config::SERVICES => [],
            Config::CORE => [],
            'test' => [
                'value' => 1
            ]
        ], $config->getConfig());
    }

}