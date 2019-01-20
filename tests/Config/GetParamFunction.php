<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace tests\Config;


use ArekX\JsonQL\Config\Config;

trait GetParamFunction
{
    public function testParamsArePassed()
    {
        $config = new Config([], ['param' => 'value']);
        $this->assertEquals($config->getParams(), ['param' => 'value']);
    }

    public function testParamIsReturned()
    {
        $config = new Config([], ['param' => 'value']);
        $this->assertEquals($config->getParam('param'), 'value');
    }

    public function testDefaultValueForParamIsReturned()
    {
        $config = new Config([], ['param' => 'value']);
        $this->assertEquals($config->getParam('param.value', 'default value'), 'default value');
    }

    public function testDefaultValueIsReturnedForEmptyParams()
    {
        $config = new Config([], []);
        $this->assertEquals($config->getParam('param.value', 'default value'), 'default value');
    }
}