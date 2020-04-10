<?php
/**
 * @author Aleksandar Panic
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

namespace tests\DI;


use ArekX\RestFn\DI\Injector;
use tests\TestCase;

class InjectorTest extends TestCase
{
    public function testCreation()
    {
        $injector = new Injector();
        $this->assertNotEmpty($injector);
    }
}