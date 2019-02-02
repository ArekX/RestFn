<?php
/**
  * @author Aleksandar Panic
  * @link https://jsonql.readthedocs.io/
  * @license: http://www.apache.org/licenses/LICENSE-2.0
  * @since 1.0.0
 **/

namespace tests\Helpers\DI;


use ArekX\JsonQL\Helpers\DI;

trait MakeFunction
{
    public function testMakeClass()
    {
        $instance = DI::make(CustomClass::class);
        $this->assertInstanceOf(CustomClass::class, $instance);
    }
    

    public function testMakeClassWithParams()
    {
        $instance = DI::make(CustomClassWithParameters::class, [
            'param1' => 'test',
            'param2' => 'value'
        ]);
        $this->assertInstanceOf(CustomClassWithParameters::class, $instance);
        $this->assertEquals('test', $instance->param1);
        $this->assertEquals('value', $instance->param2);
    }
}