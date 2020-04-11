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

use ArekX\RestFn\DI\Contracts\Injectable;
use ArekX\RestFn\DI\Contracts\SharedInstance;

/**
 * Class Injector
 * @package ArekX\RestFn\DI
 * @since 1.0.0
 *
 * Represents class for an injector handling dependency injection.
 */
class Injector
{
    protected $shared = [];

    /**
     * Injector constructor
     *
     * @param array $config Injector configuration for configuring specific classes.
     */
    public function __construct(array $config = [])
    {
    }

    /**
     * Creates instance from a class.
     *
     * @see Injector::resolveDefinition() For how definitions are resolved.
     * @see Injector::resolveBlueprint() For how class blueprint for injectables are resolved.
     *
     * @see Factory For classes which are factory providers
     * @see Injectable For classes which should be instantiated only once
     * @see SharedInstance For classes which should be instantiated only once
     * @see Configurable For classes to be auto-wired
     *
     * @param string|array $definition Class which will be resolved to create instance from.
     * @param mixed ...$args Constructor arguments passed to the class constructor.
     * @return mixed Created instance or existing instance if the class implements SharedInstance
     * @throws \ReflectionException
     */
    public function make($definition, ...$args)
    {
        [$className, $resolvedArgs] = $this->resolveDefinition($definition, $args);

        if (!empty($this->shared[$className])) {
            return $this->shared[$className];
        }

        $blueprint = $this->resolveBlueprint($className);

        /** @var \ReflectionClass $reflection */
        $reflection = $blueprint['reflection'];

        $instance = $reflection->newInstanceWithoutConstructor();

        if ($instance instanceof SharedInstance) {
            $this->share($instance);
        }

        if ($instance instanceof Injectable) {
            foreach ($blueprint['dependencies'] as $property => $type) {
                $instance->{$property} = $this->make($type);
            }
        }

        if ($blueprint['construct']) {
            $instance->__construct(...$resolvedArgs);
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
     * Resolves definition of a class from various types.
     *
     * This resolution accepts two types, string and array.
     *
     * If a resolution is a string then that string is treated as a class name.
     *
     * Classes in array format are defined as follows:
     * ```php
     * [InjectionClass::class, ['key' => 'value']]
     * ```
     * Will result in a class where first parameter of it's constructor is the second parameter of this array.
     * All other parameters in the from $args are passed after that.
     *
     * Classes in string format are created as that class with all of it's arguments from $args passed directly
     * to the constructor.
     *
     * @param array|string $definition Definition which will be resolved
     * @param array $args Arguments to be passed to constructor.
     * @return array Array with first value containing string class name and second value containing constructor args.
     */
    protected function resolveDefinition($definition, $args = [])
    {
        if (is_array($definition)) {
            return count($definition) === 2
                ? [$definition[0], [$definition[1], ...$args]]
                : [$definition[0], $args];
        }

        return [$definition, $args];
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