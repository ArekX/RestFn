<?php
/**
  * @author Aleksandar Panic
  * @link https://jsonql.readthedocs.io/
  * @license: http://www.apache.org/licenses/LICENSE-2.0
  * @since 1.0.0
 **/

namespace tests\Helpers\DI;


use ArekX\JsonQL\Helpers\DI;
use DI\Container;

trait WireClassFunction
{
    public function testClassWillBeAutoWired()
    {
        $setup = [
            'key' => 'value',
            'key2' => true
        ];
        /** @var Container $di */
        $di = $this->di;
        $di->set(CustomClass::class, DI::wireSetup(CustomClassSetup::class, $setup));
        $instance = DI::make(CustomClass::class);
        $this->assertInstanceOf(CustomClassSetup::class, $instance);
        $this->assertEquals($setup, $instance->setup);
    }
}