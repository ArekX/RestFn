<?php

/**
 * Copyright 2025 Aleksandar Panic
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 **/

namespace tests\DI;


use ArekX\RestFn\DI\Container;
use tests\DI\_mock\DummyClass;
use tests\DI\_mock\DummyInjectableClass;
use tests\DI\_mock\DummyNonInjectableClass;
use tests\TestCase;

class ContainerInjectableTest extends TestCase
{
    /**
     * @throws \ReflectionException
     * @throws \ArekX\RestFn\DI\Exceptions\ConfigNotSpecifiedException
     */
    public function testCreateInjectable()
    {
        $injector = new Container();

        /** @var DummyInjectableClass $value */
        $value = $injector->make(DummyInjectableClass::class);

        /** @var DummyInjectableClass $value2 */
        $value2 = $injector->make(DummyInjectableClass::class);

        $this->assertNotSame($value, $value2);
        $this->assertNotSame($value2->dummyClass, $value->dummyClass);

        $this->assertInstanceOf(DummyInjectableClass::class, $value, 'Injectable is valid class.');
        $this->assertInstanceOf(DummyClass::class, $value->dummyClass, 'Injectable has auto-wired class.');
        $this->assertNull($value->test, 'Non-typed properties are null.');
        $this->assertEquals("", $value->str, 'Strings are not injected.');
        $this->assertEquals([], $value->arr, 'Arrays are not injected.');
        $this->assertEquals(0, $value->int, 'Integers are not injected.');
        $this->assertEquals(0, $value->float, 'Floats are not injected.');
        $this->assertEquals(false, $value->bool, 'Booleans are not injected.');
    }

    /**
     * @throws \ReflectionException
     * @throws \ArekX\RestFn\DI\Exceptions\ConfigNotSpecifiedException
     */
    public function testNonInjectablesDoNotGetClassesAutoWired()
    {
        $injector = new Container();

        /** @var DummyNonInjectableClass $test */
        $test = $injector->make(DummyNonInjectableClass::class);

        $this->assertNull($test->dummyClass);
    }
}
