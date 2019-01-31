<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace tests\Config;


use ArekX\JsonQL\Config\Config;
use tests\Mock\MockConfig;

trait GetParamFunction
{
    public function testParamsArePassed()
    {
        $config = new MockConfig([], ['param' => 'value']);
        $this->assertEquals($config->getParams(), ['param' => 'value']);
    }

    public function testParamIsReturned()
    {
        $config = new MockConfig([], ['param' => 'value']);
        $this->assertEquals($config->getParam('param'), 'value');
    }

    public function testDefaultValueForParamIsReturned()
    {
        $config = new MockConfig([], ['param' => 'value']);
        $this->assertEquals($config->getParam('param.value', 'default value'), 'default value');
    }

    public function testDefaultValueIsReturnedForEmptyParams()
    {
        $config = new MockConfig([], []);
        $this->assertEquals($config->getParam('param.value', 'default value'), 'default value');
    }
}