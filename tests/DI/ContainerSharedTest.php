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
use tests\DI\_mock\DummyOverrideClass;
use tests\DI\_mock\DummySharedClass;
use tests\TestCase;

class ContainerSharedTest extends TestCase
{
    /**
     * @throws \ReflectionException
     * @throws \ArekX\RestFn\DI\Exceptions\ConfigNotSpecifiedException
     */
    public function testShareInstance()
    {
        $container = new Container();

        $test = new DummyClass();

        $container->share($test);

        $newInstance = $container->make(DummyClass::class);

        $this->assertSame($test, $newInstance, 'New instance is same for singleton.');
    }

    /**
     * @throws \ReflectionException
     * @throws \ArekX\RestFn\DI\Exceptions\ConfigNotSpecifiedException
     */
    public function testShareClassName()
    {
        $container = new Container();

        $container->share(DummyClass::class);

        $test = $container->make(DummyClass::class);
        $newInstance = $container->make(DummyClass::class);

        $this->assertSame($test, $newInstance, 'New instance is same for singleton.');
    }

    /**
     * @throws \ReflectionException
     * @throws \ArekX\RestFn\DI\Exceptions\ConfigNotSpecifiedException
     */
    public function testInstancesImplementingSharedAreAutomaticallyShared()
    {
        $container = new Container();

        $test = $container->make(DummySharedClass::class);
        $newInstance = $container->make(DummySharedClass::class);

        $this->assertSame($test, $newInstance, 'New instance is same for singleton.');
    }


    /**
     * @throws \ReflectionException
     * @throws \ArekX\RestFn\DI\Exceptions\ConfigNotSpecifiedException
     */
    public function testShareClassNameIsBeingAlwaysAliased()
    {
        $container = new Container();

        $container->alias(DummyClass::class, DummyOverrideClass::class);

        $container->share(DummyClass::class);

        $test = $container->make(DummyClass::class);
        $newInstance = $container->make(DummyClass::class);

        $this->assertInstanceOf(DummyOverrideClass::class, $test);
        $this->assertInstanceOf(DummyOverrideClass::class, $newInstance);

        $this->assertSame($test, $newInstance, 'New instance is same for singleton.');
    }
}
