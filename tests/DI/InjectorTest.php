<?php
/**
 * Copyright 2020 Aleksandar Panic
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


use ArekX\RestFn\DI\Injector;
use tests\DI\_mock\DummyClass;
use tests\DI\_mock\DummyClassWithArgs;
use tests\TestCase;

class InjectorTest extends TestCase
{
    /**
     * @throws \ReflectionException
     */
    public function testMakeCreatesAnInstance()
    {
        $injector = new Injector();
        $value = $injector->make(DummyClass::class);
        $this->assertInstanceOf(DummyClass::class, $value, 'Created is of type of DummyClass.');
    }

    /**
     * @throws \ReflectionException
     */
    public function testMakeWithConstructorArguments()
    {
        $injector = new Injector();

        /** @var DummyClassWithArgs $value */
        $value = $injector->make(DummyClassWithArgs::class, 'test1', 'test2');

        $this->assertEquals('test1', $value->arg1, 'Arg1 is set.');
        $this->assertEquals('test2', $value->arg2, 'Arg2 is set.');
    }

    /**
     * @throws \ReflectionException
     */
    public function testMakeClassUsingArray()
    {
        $injector = new Injector();

        $value = $injector->make([DummyClass::class]);

        $this->assertInstanceOf(DummyClass::class, $value, 'Created is of type of DummyClass.');
    }

    /**
     * @throws \ReflectionException
     */
    public function testMakeClassUsingArrayArgs()
    {
        $injector = new Injector();

        $config = [DummyClassWithArgs::class, ['test' => 'v1', 'test2' => 'v2']];

        /** @var DummyClassWithArgs $value */
        $value = $injector->make($config, 'arg2');
        $this->assertInstanceOf(DummyClassWithArgs::class, $value, 'Created is of type of DummyClassWithArgs.');

        $this->assertEquals($config[1], $value->arg1);
        $this->assertEquals('arg2', $value->arg2);
    }
}