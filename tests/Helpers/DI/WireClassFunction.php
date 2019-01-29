<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace tests\Helpers\DI;


use ArekX\JsonQL\Helpers\DI;

trait WireClassFunction
{
    public function testClassWillBeAutoWired()
    {
        $setup = [
            'key' => 'value',
            'key2' => true
        ];
        $this->di->set(CustomClass::class, DI::wireSetup(CustomClassSetup::class, $setup));
        $instance = DI::make(CustomClass::class);
        $this->assertInstanceOf(CustomClassSetup::class, $instance);
        $this->assertEquals($setup, $instance->setup);
    }
}