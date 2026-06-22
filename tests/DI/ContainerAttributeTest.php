<?php

/**
 * Copyright 2026 Aleksandar Panic
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
use ArekX\RestFn\DI\Exceptions\UnresolvedParameterException;
use Psr\Container\ContainerInterface;
use tests\DI\_mock\DummyClass;
use tests\DI\_mock\DummyClassWithArgs;
use tests\DI\_mock\DummyConfigInjectable;
use tests\DI\_mock\DummyConstructorAutowire;
use tests\DI\_mock\DummyConstructorInject;
use tests\DI\_mock\DummyContainerAware;
use tests\DI\_mock\DummyOverrideClass;
use tests\TestCase;

class ContainerAttributeTest extends TestCase
{
    public function testConfigAttributeReadsGlobal()
    {
        $container = new Container([
            'config' => ['global' => ['limits' => ['maxDepth' => 64]]],
        ]);

        /** @var DummyConfigInjectable $value */
        $value = $container->make(DummyConfigInjectable::class);

        $this->assertSame(64, $value->maxDepth);
    }

    public function testPerClassOverrideWinsOverGlobal()
    {
        $container = new Container([
            'config' => [
                'global' => ['limits' => ['maxDepth' => 64]],
                'overrides' => [
                    DummyConfigInjectable::class => ['limits' => ['maxDepth' => 99]],
                ],
            ],
        ]);

        /** @var DummyConfigInjectable $value */
        $value = $container->make(DummyConfigInjectable::class);

        $this->assertSame(99, $value->maxDepth);
    }

    public function testConfigAttributeFallsBackToDefault()
    {
        $container = new Container();

        /** @var DummyConfigInjectable $value */
        $value = $container->make(DummyConfigInjectable::class);

        $this->assertSame(10, $value->maxDepth);
        $this->assertSame('fallback', $value->fallback);
        $this->assertTrue($value->flag);
    }

    public function testFalsyConfigValueIsHonoredOverDefault()
    {
        $container = new Container([
            'config' => ['global' => ['limits' => ['maxDepth' => 0, 'flag' => false]]],
        ]);

        /** @var DummyConfigInjectable $value */
        $value = $container->make(DummyConfigInjectable::class);

        $this->assertSame(0, $value->maxDepth);
        $this->assertFalse($value->flag);
    }

    public function testInjectAttributeResolvesObjects()
    {
        $container = new Container();

        /** @var DummyConfigInjectable $value */
        $value = $container->make(DummyConfigInjectable::class);

        $this->assertInstanceOf(DummyClass::class, $value->explicitObject);
        $this->assertInstanceOf(DummyClass::class, $value->typedObject);
    }

    public function testConstructorAutowiresTypeAndConfig()
    {
        $container = new Container([
            'config' => ['global' => ['limits' => ['maxDepth' => 7]]],
        ]);

        /** @var DummyConstructorAutowire $value */
        $value = $container->make(DummyConstructorAutowire::class);

        $this->assertInstanceOf(DummyClass::class, $value->service);
        $this->assertSame(7, $value->limit);
        $this->assertSame('default', $value->name);
    }

    public function testInjectAttributeResolvesConstructorParameters()
    {
        $container = new Container();

        /** @var DummyConstructorInject $value */
        $value = $container->make(DummyConstructorInject::class);

        $this->assertInstanceOf(DummyOverrideClass::class, $value->explicit);
        $this->assertInstanceOf(DummyClass::class, $value->typed);
    }

    public function testInjectedContainerIsTheConfiguredContainer()
    {
        $container = new Container();

        /** @var DummyContainerAware $value */
        $value = $container->make(DummyContainerAware::class);

        $this->assertSame($container, $value->container);
    }

    public function testConstructorOverrideByName()
    {
        $container = new Container();

        /** @var DummyConstructorAutowire $value */
        $value = $container->make(DummyConstructorAutowire::class, ['name' => 'custom']);

        $this->assertSame('custom', $value->name);
        $this->assertSame(5, $value->limit, 'Config default is used when not overridden.');
    }

    public function testUnresolvableConstructorParameterThrows()
    {
        $container = new Container();

        $this->expectException(UnresolvedParameterException::class);

        // DummyClassWithArgs has untyped, attribute-less, default-less params.
        $container->make(DummyClassWithArgs::class);
    }

    public function testContainerSharesItself()
    {
        $container = new Container();

        $this->assertSame($container, $container->make(Container::class));
        $this->assertSame($container, $container->make(ContainerInterface::class));
    }
}
