<?php
/**
  * @author Aleksandar Panic
  * @link https://jsonql.readthedocs.io/
  * @license: http://www.apache.org/licenses/LICENSE-2.0
  * @since 1.0.0
 **/

namespace tests\Config;


use ArekX\JsonQL\Config\Config;
use tests\Mock\MockConfig;

trait GetParamFunction
{
    public function testParamsArePassed()
    {
        $config = new MockConfig([], ['param' => 'value']);
        $this->assertEquals(['param' => 'value'], $config->getParams());
    }

    public function testParamIsReturned()
    {
        $config = new MockConfig([], ['param' => 'value']);
        $this->assertEquals('value', $config->getParam('param'));
    }

    public function testDefaultValueForParamIsReturned()
    {
        $config = new MockConfig([], ['param' => 'value']);
        $this->assertEquals('default value', $config->getParam('param.value', 'default value'));
    }

    public function testDefaultValueIsReturnedForEmptyParams()
    {
        $config = new MockConfig([], []);
        $this->assertEquals('default value', $config->getParam('param.value', 'default value'));
    }
}