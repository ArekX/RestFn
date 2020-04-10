<?php
/**
 * @author Aleksandar Panic
 * @link https://restfn.readthedocs.io/
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

namespace tests\DI;


use ArekX\RestFn\DI\Injector;
use tests\DI\_mock\DummyClass;
use tests\TestCase;

class InjectorTest extends TestCase
{
    public function testMakeCreatesAClass()
    {
        $injector = new Injector();
        $value = $injector->make(DummyClass::class);
        $this->assertInstanceOf(DummyClass::class, $value);
    }
}