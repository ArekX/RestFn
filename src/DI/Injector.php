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

namespace ArekX\RestFn\DI;

use ArekX\RestFn\DI\Contracts\Configurable;
use ArekX\RestFn\DI\Contracts\Injectable;
use ArekX\RestFn\DI\Contracts\SharedInstance;
use ArekX\RestFn\DI\Exceptions\ConfigNotSpecifiedException;

/**
 * Class Injector
 * @package ArekX\RestFn\DI
 * @since 1.0.0
 *
 * Represents class for an injector handling dependency injection.
 */
class Injector
{
    /**
     * Contains key => value storage which is a list of current shared instances
     * in this injector.
     *
     * @var SharedInstance[]
     */
    protected $shared = [];

    /**
     * Contains className => config storage which contains configurations for all classes
     * which implement Configurable.
     *
     * During creation this config will be passed to instances configure() function.
     *
     * @see Configurable::configure()
     * @var array
     */
    protected $configMap = [];

    /**
     * Injector constructor
     *
     * @param array $configMap Configuration map to be passed for classes implementing Configurable
     * @see Configurable::configure()
     * @see Injector::$configMap
     */
    public function __construct(array $configMap = [])
    {
        $this->configMap = $configMap;
    }

    /**
     * Creates instance from a class.
     *
     * @param string|array $definition Class which will be resolved to create instance from.
     * @param mixed ...$args Constructor arguments passed to the class constructor.
     * @return mixed Created instance or existing instance if the class implements SharedInstance
     * @throws \ReflectionException*@throws ConfigNotSpecifiedException
     * @throws ConfigNotSpecifiedException
     * @see Injector::resolveDefinition() For how definitions are resolved.
     * @see Injector::resolveBlueprint() For how class blueprint for injectables are resolved.
     *
     * @see Factory For classes which are factory providers
     * @see Injectable For classes which should be instantiated only once
     * @see SharedInstance For classes which should be instantiated only once
     * @see Configurable For classes to be auto-wired
     *
     */
    public function make($className, ...$args)
    {
        if (!empty($this->shared[$className])) {
            return $this->shared[$className];
        }

        $blueprint = $this->resolveBlueprint($className);

        /** @var \ReflectionClass $reflection */
        $reflection = $blueprint['reflection'];

        /** @var object $instance */
        $instance = $reflection->newInstanceWithoutConstructor();

        if ($instance instanceof SharedInstance) {
            $this->share($instance);
        }

        if ($instance instanceof Injectable) {
            foreach ($blueprint['dependencies'] as $property => $type) {
                $instance->{$property} = $this->make($type);
            }
        }

        if ($instance instanceof Configurable) {
            if (empty($this->configMap[$className])) {
                throw new ConfigNotSpecifiedException($className);
            }

            $instance->configure($this->configMap[$className]);
        }

        if ($blueprint['construct']) {
            $instance->__construct(...$args);
        }

        return $instance;
    }

    /**
     * Marks one definition to be shared across all calls to make.
     *
     * If a definition is an object it will be shared directly by using it's class name.
     *
     * If a definition is not an object it will be created using make() function.
     *
     * @param array|string|object $definition Definition to be resolved.
     * @param mixed ...$args Arguments to be passed to make().
     * @return object Passed or created definition.
     * @throws \ReflectionException
     * @throws ConfigNotSpecifiedException
     * @see Injector::make()
     */
    public function share($definition, ...$args)
    {
        if (!is_object($definition)) {
            $definition = $this->make($definition, ...$args);
        }

        return $this->shared[get_class($definition)] = $definition;
    }

    /**
     * Sets configuration for specified class.
     *
     * This configuration will be passed during the class creation in the call to make().
     *
     * Class must implement Configurable for this configuration to be passed to it.
     *
     * @param string $className Class which will be configured.
     * @param array $config Configuration to be passed to classes configure method.
     * @see Configurable
     */
    public function configure($className, array $config)
    {
        $this->configMap[$className] = $config;
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
            'dependencies' => $dependencyMap
        ];
    }
}