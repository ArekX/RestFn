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
use tests\DI\_mock\DummyClassWithArgs;
use tests\DI\_mock\MockFactory;
use tests\TestCase;

class ContainerFactoryTest extends TestCase
{
    /**
     * @throws \ReflectionException
     * @throws \ArekX\RestFn\DI\Exceptions\ConfigNotSpecifiedException
     */
    public function testFactoryMakeWorks()
    {
        $injector = new Container([
            'factories' => [
                DummyClass::class => MockFactory::class
            ],
        ]);

        /** @var MockFactory $factory */
        $factory = $injector->make(MockFactory::class);


        $this->assertFalse($factory->wasCalled());

        /** @var DummyClass $newInstance */
        $newInstance = $injector->make(DummyClass::class);

        $this->assertInstanceOf(DummyClass::class, $newInstance);
        $this->assertTrue($factory->wasCalled());
    }

    /**
     * @throws \ReflectionException
     * @throws \ArekX\RestFn\DI\Exceptions\ConfigNotSpecifiedException
     */
    public function testFactoryMakeWorksFromMethod()
    {
        $injector = new Container();

        $injector->factory(DummyClass::class, MockFactory::class);

        /** @var MockFactory $factory */
        $factory = $injector->make(MockFactory::class);


        $this->assertFalse($factory->wasCalled());

        /** @var DummyClass $newInstance */
        $newInstance = $injector->make(DummyClass::class);

        $this->assertInstanceOf(DummyClass::class, $newInstance);
        $this->assertTrue($factory->wasCalled());
    }

    /**
     * @throws \ReflectionException
     * @throws \ArekX\RestFn\DI\Exceptions\ConfigNotSpecifiedException
     */
    public function testFactoryMakeWorksWithArguments()
    {
        $injector = new Container([
            'factories' => [
                DummyClassWithArgs::class => MockFactory::class
            ],
        ]);

        /** @var MockFactory $factory */
        $factory = $injector->make(MockFactory::class);

        $this->assertFalse($factory->wasCalled());

        /** @var DummyClassWithArgs $newInstance */
        $newInstance = $injector->make(DummyClassWithArgs::class, 'a', 'b');

        $this->assertInstanceOf(DummyClassWithArgs::class, $newInstance);
        $this->assertEquals('a', $newInstance->arg1);
        $this->assertEquals('b', $newInstance->arg2);
        $this->assertTrue($factory->wasCalled());
    }


    /**
     * @throws \ReflectionException
     * @throws \ArekX\RestFn\DI\Exceptions\ConfigNotSpecifiedException
     */
    public function testFactoryNotCalledIfNotMapped()
    {
        $injector = new Container([
            'factories' => [
                DummyClassWithArgs::class => MockFactory::class
            ],
        ]);

        /** @var MockFactory $factory */
        $factory = $injector->make(MockFactory::class);

        $this->assertFalse($factory->wasCalled());

        /** @var DummyClass $newInstance */
        $newInstance = $injector->make(DummyClass::class);

        $this->assertInstanceOf(DummyClass::class, $newInstance);
        $this->assertFalse($factory->wasCalled());
    }


    /**
     * @throws \ReflectionException
     * @throws \ArekX\RestFn\DI\Exceptions\ConfigNotSpecifiedException
     */
    public function testDisabledFactoryNotCalled()
    {
        $injector = new Container([
            'factories' => [
                DummyClass::class => MockFactory::class
            ],
        ]);

        $injector->disableFactory(MockFactory::class);

        /** @var MockFactory $factory */
        $factory = $injector->make(MockFactory::class);

        $this->assertFalse($factory->wasCalled());

        /** @var DummyClass $newInstance */
        $injector->make(DummyClass::class);

        $this->assertFalse($factory->wasCalled());
    }

    /**
     * @throws \ReflectionException
     * @throws \ArekX\RestFn\DI\Exceptions\ConfigNotSpecifiedException
     */
    public function testDisabledThenEnabledFactoryIsCalled()
    {
        $injector = new Container([
            'factories' => [
                DummyClass::class => MockFactory::class
            ],
        ]);

        $injector->disableFactory(MockFactory::class);

        /** @var MockFactory $factory */
        $factory = $injector->make(MockFactory::class);

        $this->assertFalse($factory->wasCalled());

        $injector->enableFactory(MockFactory::class);

        /** @var DummyClass $newInstance */
        $injector->make(DummyClass::class);

        $this->assertTrue($factory->wasCalled());
    }
}
