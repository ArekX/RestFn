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
        $config = new Config([
            'test' => [
                'value' => 1
            ]
        ]);

        $this->assertEquals($config->getConfig(), [
            Config::DI => [
                'compile' => false
            ],
            Config::SERVICES => [],
            Config::CORE => [],
            'test' => [
                'value' => 1
            ]
        ]);
    }

}