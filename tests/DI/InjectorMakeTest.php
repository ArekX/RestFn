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


use ArekX\RestFn\DI\Exceptions\ConfigNotSpecifiedException;
use ArekX\RestFn\DI\Injector;
use tests\DI\_mock\DummyClass;
use tests\DI\_mock\DummyClassWithArgs;
use tests\DI\_mock\DummyConfigurableClass;
use tests\DI\_mock\DummyConfigurableClassWithArgs;
use tests\TestCase;

class InjectorMakeTest extends TestCase
{
    /**
     * @throws \ReflectionException
     * @throws ConfigNotSpecifiedException
     */
    public function testMakeCreatesAnInstance()
    {
        $injector = new Injector();
        $value = $injector->make(DummyClass::class);
        $this->assertInstanceOf(DummyClass::class, $value, 'Created is of type of DummyClass.');
    }

    /**
     * @throws \ReflectionException
     * @throws ConfigNotSpecifiedException
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
     * @throws ConfigNotSpecifiedException
     */
    public function testMakeClassUsingConfigurable()
    {
        $testConfig = [
            'test' => 1
        ];
        $injector = new Injector([
            DummyConfigurableClass::class => $testConfig
        ]);

        /** @var DummyConfigurableClass $value */
        $value = $injector->make(DummyConfigurableClass::class);

        $this->assertInstanceOf(DummyConfigurableClass::class, $value, 'Created is of type of DummyClass.');
        $this->assertEquals($value->passedConfig, $testConfig, 'Config is passed correctly.');
    }

    /**
     * @throws \ReflectionException
     * @throws ConfigNotSpecifiedException
     */
    public function testMadeClassConfigureIsCalledAfterConstructor()
    {
        $testConfig = [
            'test' => 1
        ];
        $injector = new Injector([
            DummyConfigurableClass::class => $testConfig
        ]);

        /** @var DummyConfigurableClass $value */
        $value = $injector->make(DummyConfigurableClass::class);

        $this->assertEquals($value->callStack, [
            'tests\DI\_mock\DummyConfigurableClass::configure',
            'tests\DI\_mock\DummyConfigurableClass::__construct',
        ], 'Config is passed correctly.');
    }

    /**
     * @throws \ReflectionException
     * @throws ConfigNotSpecifiedException
     */
    public function testMakeClassUsingConfigurableWithArgs()
    {
        $testConfig = [
            'test' => 1
        ];
        $injector = new Injector([
            DummyConfigurableClassWithArgs::class => $testConfig
        ]);

        /** @var DummyConfigurableClassWithArgs $value */
        $value = $injector->make(DummyConfigurableClassWithArgs::class, 'arg1', 'arg2');

        $this->assertInstanceOf(DummyConfigurableClassWithArgs::class, $value, 'Created is of type of DummyClassWithArgs.');
        $this->assertEquals($value->passedConfig, $testConfig, 'Config is passed correctly.');
        $this->assertEquals('arg1', $value->arg1);
        $this->assertEquals('arg2', $value->arg2);
    }

    /**
     * @throws \ReflectionException
     * @throws ConfigNotSpecifiedException
     */
    public function testMakeConfigurableWithNoMappedConfiguration()
    {
        $injector = new Injector([]);

        $this->expectException(ConfigNotSpecifiedException::class);

        $injector->make(DummyConfigurableClass::class, 'arg1', 'arg2');
    }

    /**
     * @throws \ReflectionException
     * @throws ConfigNotSpecifiedException
     */
    public function testPassConfigThroughMethod()
    {
        $injector = new Injector([]);

        $testConfig = ['test1' => 'config1'];
        $injector->configure(DummyConfigurableClass::class, $testConfig);

        /** @var DummyConfigurableClass $value */
        $value = $injector->make(DummyConfigurableClass::class, 'arg1', 'arg2');

        $this->assertInstanceOf(DummyConfigurableClass::class, $value, 'Created is of type of DummyClassWithArgs.');
        $this->assertEquals($value->passedConfig, $testConfig, 'Config is passed correctly.');
    }
}