<?php
/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
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