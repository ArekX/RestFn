<?php

declare(strict_types=1);


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

namespace ArekX\RestFn\DI;

use ArekX\RestFn\DI\Contracts\ConfigurableInterface;
use ArekX\RestFn\DI\Contracts\FactoryInterface;
use ArekX\RestFn\DI\Contracts\InjectableInterface;
use ArekX\RestFn\DI\Contracts\SharedInstanceInterface;
use ArekX\RestFn\DI\Exceptions\ConfigNotSpecifiedException;
use Psr\Container\ContainerInterface;

/**
 * Class Injector
 * @package ArekX\RestFn\DI
 * @since 1.0.0
 *
 * Represents class for an injector handling dependency injection.
 */
class Container implements ContainerInterface
{
    /**
     * Contains key => value storage which is a list of current shared instances
     * in this injector.
     *
     * @var SharedInstanceInterface[]
     */
    protected $shared = [];

    /**
     * Contains className => config storage which contains configurations for all classes
     * which implement Configurable.
     *
     * During creation this config will be passed to instances configure() function.
     *
     * @see ConfigurableInterface::configure()
     * @var array
     */
    protected $configMap = [];

    /**
     * Contains all aliases for aliasing between definitions when calling make.
     *
     * @var array
     */
    protected $aliasMap = [];

    /**
     * Contains key, value map of factories for specific classes.
     *
     * @see Container::factory()
     * @var array
     */
    protected $factoryMap = [];

    /**
     * Contains key, value maps of disabled factory classes.
     *
     * If a disabled factory is set then it will not be used during any
     * subsequent calls to make().
     *
     * @var array
     */
    protected $enabledFactories = [];

    /**
     * Injector constructor
     *
     * @param array $configMap Configuration map to be passed for classes implementing Configurable
     * @see ConfigurableInterface::configure()
     * @see Container::$configMap
     */
    public function __construct(array $config = [])
    {
        $this->aliasMap = $config['aliases'] ?? [];

        $configurations = $config['configurations'] ?? [];
        foreach ($configurations as $definition => $config) {
            $this->configure($definition, $config);
        }

        $factories = $config['factories'] ?? [];
        foreach ($factories as $definition => $factoryClass) {
            $this->factory($definition, $factoryClass);
        }
    }

    /**
     * Gets an instance from the container.
     *
     * @param string $id Identifier of the entry to look for.
     * @return mixed Entry.
     * @throws \ReflectionException
     * @throws ConfigNotSpecifiedException
     * @see Container::make()
     */
    #[\Override]
    public function get(string $id)
    {
        return $this->make($id);
    }

    /**
     * Checks if the container can return an entry for the given identifier.
     *
     * This method checks if the identifier exists in shared instances, has a registered factory,
     * or if the class/interface exists.
     *
     * @param string $id Identifier of the entry to look for.
     * @return bool True if the container can provide the entry, false otherwise.
     * @see Container::make()
     * @see Container::resolveAlias()
     */
    #[\Override]
    public function has(string $id): bool
    {
        $definition = $this->resolveAlias($id);

        if (!empty($this->shared[$definition])) {
            return true;
        }

        if (!empty($this->factoryMap[$definition])) {
            return true;
        }

        return class_exists($definition) || interface_exists($definition);
    }

    /**
     * Creates instance from a class.
     *
     * @param string $definition Class which will be resolved to create instance from.
     * @param mixed ...$args Constructor arguments passed to the class constructor.
     * @return mixed Created instance or existing instance if the class implements SharedInstance
     * @throws \ReflectionException
     *
     * @throws ConfigNotSpecifiedException
     *
     * @see Container::makeFromBlueprint()
     * @see FactoryInterface For classes which are factory providers
     * @see InjectableInterface For classes which should be instantiated only once
     * @see SharedInstanceInterface For classes which should be instantiated only once
     * @see ConfigurableInterface For classes to be auto-wired
     *
     */
    public function make(string $definition, ...$args)
    {
        $definition = $this->resolveAlias($definition);

        if (!empty($this->shared[$definition])) {
            return $this->shared[$definition];
        }

        if (!empty($this->factoryMap[$definition])) {
            return $this->makeUsingFactory($definition, $args);
        }

        return $this->makeFromBlueprint($definition, $args);
    }

    /**
     * Marks one definition to be shared across all calls to make.
     *
     * If a definition is an object it will be shared directly by using it's class name.
     *
     * If a definition is not an object it will be created using make() function.
     *
     * @param string|object $definition Definition to be resolved.
     * @param mixed ...$args Arguments to be passed to make().
     * @return object Passed or created definition.
     * @throws \ReflectionException
     * @throws ConfigNotSpecifiedException
     * @see Container::make()
     */
    public function share($definition, ...$args)
    {
        if (!is_object($definition)) {
            $definition = $this->make($definition, ...$args);
        }

        return $this->shared[get_class($definition)] = $definition;
    }

    /**
     * Sets factory class for a specific class.
     *
     * Factory classes will go through the full make() process
     * so they can be set with Configurable and SharedInstance interfaces
     * as necessary.
     *
     * When a $forClass is being made using make() function it will be made by calling
     * factory class's function create.
     *
     * Instances created through factory's create method will NOT go through the injection process
     * so if you need them to go through it you need to call Injector::make() from within that
     * factory class.
     *
     * Classes need to implement Factory to be used as factories.
     *
     * @param string $forClass class for which
     * @param string $factoryClass Factory class which will be set.
     * @see FactoryInterface
     * @see Container::make()
     */
    public function factory(string $forClass, string $factoryClass)
    {
        $this->factoryMap[$forClass] = $factoryClass;
        $this->enabledFactories[$factoryClass] = true;
    }

    /**
     * Disables factory from being resolved for a class during calls to make().
     *
     * @param string $factoryClass
     * @see Container::make()
     */
    public function disableFactory(string $factoryClass)
    {
        $this->enabledFactories[$factoryClass] = false;
    }

    /**
     * Enables factory so it's being resolved for a class during calls to make().
     *
     * @param string $factoryClass
     * @see Container::make()
     */
    public function enableFactory(string $factoryClass)
    {
        $this->enabledFactories[$factoryClass] = true;
    }

    /**
     * Sets configuration for specified class.
     *
     * This configuration will be passed during the class creation in the call to make().
     *
     * Class must implement Configurable for this configuration to be passed to it.
     *
     * @param string $definition Class which will be configured.
     * @param array $config Configuration to be passed to classes configure method.
     * @see ConfigurableInterface
     */
    public function configure($definition, array $config)
    {
        $this->configMap[$definition] = $config;
    }

    /**
     * Sets alias definition between definitions.
     *
     * @param string $definition
     * @param string $withDefinition
     */
    public function alias($definition, $withDefinition)
    {
        $this->aliasMap[$definition] = $withDefinition;
    }

    /**
     * Resolve blueprint of the class for injection.
     *
     * Resolved blueprints are cached in the static variable
     * cache in this function to improve resolution performance.
     *
     * @param $class
     * @return array|mixed
     * @throws \ReflectionException
     */
    protected function resolveBlueprint($class)
    {
        static $cache = [];

        if (!empty($cache[$class])) {
            return $cache[$class];
        }

        $reflection = new \ReflectionClass($class);

        $dependencyMap = [];

        foreach ($reflection->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
            if (!$property->hasType()) {
                continue;
            }

            /** @var \ReflectionNamedType $type */
            $type = $property->getType();

            $typeName = $type->getName();
            if (!class_exists($typeName) && !interface_exists($typeName)) {
                continue;
            }

            $dependencyMap[$property->getName()] = $typeName;
        }

        return $cache[$class] = [
            'reflection' => $reflection,
            'construct' => $reflection->getConstructor() !== null,
            'dependencies' => $dependencyMap,
        ];
    }

    /**
     * Creates instance from blueprint.
     *
     * @param string $class
     * @param $args
     * @return object
     *
     * @throws ConfigNotSpecifiedException
     * @throws \ReflectionException
     * @see Container::resolveDefinition() For how definitions are resolved.
     * @see Container::resolveBlueprint() For how class blueprint for injectables are resolved.
     */
    protected function makeFromBlueprint(string $class, $args): object
    {
        $blueprint = $this->resolveBlueprint($class);

        /** @var \ReflectionClass $reflection */
        $reflection = $blueprint['reflection'];

        /** @var object $instance */
        $instance = $reflection->newInstanceWithoutConstructor();

        if ($instance instanceof SharedInstanceInterface) {
            $this->share($instance);
        }

        if ($instance instanceof InjectableInterface) {
            foreach ($blueprint['dependencies'] as $property => $type) {
                $instance->{$property} = $this->make($type);
            }
        }

        if ($instance instanceof ConfigurableInterface) {
            if (empty($this->configMap[$class])) {
                throw new ConfigNotSpecifiedException($class);
            }

            $instance->configure($this->configMap[$class]);
        }

        if ($blueprint['construct']) {
            $instance->__construct(...$args);
        }

        return $instance;
    }

    /**
     * Creates instance by using a factory class.
     *
     * @param string $definition Class definition to be created.
     * @param array $args Arguments passed to class definition.
     * @return mixed Instance created by factory class.
     * @throws ConfigNotSpecifiedException
     * @throws \ReflectionException
     */
    protected function makeUsingFactory(string $definition, array $args)
    {
        $factoryClass = $this->factoryMap[$definition];

        if (!$this->enabledFactories[$factoryClass]) {
            return $this->makeFromBlueprint($definition, $args);
        }

        /** @var FactoryInterface $factory */
        $factory = $this->make($factoryClass, $args);

        $this->disableFactory($factoryClass);
        $instance = $factory->create($definition, $args);
        $this->enableFactory($factoryClass);

        return $instance;
    }

    /**
     * Recursively resolve definitions through alias map.
     *
     * @param string $definition
     * @return string Resolved definition.
     */
    protected function resolveAlias(string $definition)
    {
        if (empty($this->aliasMap[$definition])) {
            return $definition;
        }

        $resolved = $this->aliasMap[$definition];

        return empty($this->aliasMap[$resolved]) ? $resolved : $this->resolveAlias($resolved);
    }
}
