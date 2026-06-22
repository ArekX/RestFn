<?php

declare(strict_types=1);

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

namespace ArekX\RestFn\DI;

use ArekX\RestFn\DI\Attributes\Config;
use ArekX\RestFn\DI\Attributes\Inject;
use ArekX\RestFn\DI\Contracts\ConfigurableInterface;
use ArekX\RestFn\DI\Contracts\FactoryInterface;
use ArekX\RestFn\DI\Contracts\SharedInstanceInterface;
use ArekX\RestFn\DI\Exceptions\CircularDependencyException;
use ArekX\RestFn\DI\Exceptions\ConfigNotSpecifiedException;
use ArekX\RestFn\DI\Exceptions\UnresolvedParameterException;
use ArekX\RestFn\Helper\Value;
use Psr\Container\ContainerInterface;

/**
 * Class Container
 * @package ArekX\RestFn\DI
 * @since 1.0.0
 *
 * Represents class for a container handling dependency injection.
 */
// @mago-ignore lint:cyclomatic-complexity
// @mago-ignore lint:kan-defect
class Container implements ContainerInterface
{
    /**
     * Contains key => value storage which is a list of current shared instances
     * in this container.
     *
     * @var SharedInstanceInterface[]
     */
    protected array $shared = [];

    /**
     * Contains className => config storage which contains configurations for all classes
     * which implement Configurable.
     *
     * During creation this config will be passed to instances configure() function.
     *
     * @see ConfigurableInterface::configure()
     * @var array
     */
    protected array $configMap = [];

    /**
     * Contains all aliases for aliasing between definitions when calling make.
     *
     * @var array
     */
    protected array $aliasMap = [];

    /**
     * Contains key, value map of factories for specific classes.
     *
     * @see Container::factory()
     * @var array
     */
    protected array $factoryMap = [];

    /**
     * Contains key, value maps of disabled factory classes.
     *
     * If a disabled factory is set then it will not be used during any
     * subsequent calls to make().
     *
     * @var array
     */
    protected array $enabledFactories = [];

    /**
     * Tracks classes currently being resolved from a blueprint so that
     * dependency cycles can be detected instead of recursing until the
     * stack is exhausted.
     *
     * @var array
     */
    protected array $resolving = [];

    /**
     * Global configuration shared across all classes.
     *
     * Read by Config-attributed properties and parameters as the base layer,
     * below any per-class override.
     *
     * @var array
     */
    protected array $globalConfig = [];

    /**
     * Cache of resolved class blueprints.
     *
     * @var array
     */
    protected array $blueprintCache = [];

    /**
     * Sentinel used to distinguish a missing config value from a real null.
     *
     * @var object
     */
    protected object $configMiss;

    /**
     * container constructor
     *
     * Expected config shape:
     * [
     *   'config' => [
     *       'global'    => [...grouped global config...],
     *       'overrides' => [ClassName::class => [...grouped per-class config...]],
     *   ],
     *   'aliases'   => [definition => withDefinition],
     *   'factories' => [forClass => factoryClass],
     * ]
     *
     * @param array $config
     * @see ConfigurableInterface::configure()
     */
    public function __construct(array $config = [])
    {
        $this->configMiss = new \stdClass();

        $this->shareSelf();

        $this->aliasMap = $config['aliases'] ?? [];

        $this->globalConfig = $config['config']['global'] ?? [];

        $overrides = $config['config']['overrides'] ?? [];
        foreach ($overrides as $definition => $override) {
            $this->configure($definition, $override);
        }

        $factories = $config['factories'] ?? [];
        foreach ($factories as $definition => $factoryClass) {
            $this->factory($definition, $factoryClass);
        }
    }

    /**
     * Registers the container itself as a shared instance so that classes can
     * inject the configured container rather than receiving a fresh one.
     */
    protected function shareSelf(): void
    {
        $this->shared[self::class] = $this;
        $this->shared[static::class] = $this;
        $this->shared[ContainerInterface::class] = $this;
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
    public function get(string $id): mixed
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
     * @param array $args Associative map of constructor parameter overrides keyed by parameter name.
     *                    Parameters not present here are autowired by attribute, type, or default.
     * @return mixed Created instance or existing instance if the class implements SharedInstance
     * @throws \ReflectionException
     *
     * @throws ConfigNotSpecifiedException
     *
     * @see Container::makeFromBlueprint()
     * @see FactoryInterface For classes which are factory providers
     * @see SharedInstanceInterface For classes which should be instantiated only once
     * @see ConfigurableInterface For classes to be auto-wired
     *
     */
    public function make(string $definition, array $args = []): mixed
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
     * @param array $args Associative constructor overrides passed to make().
     * @return object Passed or created definition.
     * @throws \ReflectionException
     * @throws ConfigNotSpecifiedException
     * @see Container::make()
     */
    public function share(string|object $definition, array $args = []): object
    {
        if (!is_object($definition)) {
            $definition = $this->make($definition, $args);
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
     * so if you need them to go through it you need to call container::make() from within that
     * factory class.
     *
     * Classes need to implement Factory to be used as factories.
     *
     * @param string $forClass class for which
     * @param string $factoryClass Factory class which will be set.
     * @see FactoryInterface
     * @see Container::make()
     */
    public function factory(string $forClass, string $factoryClass): void
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
    public function disableFactory(string $factoryClass): void
    {
        $this->enabledFactories[$factoryClass] = false;
    }

    /**
     * Enables factory so it's being resolved for a class during calls to make().
     *
     * @param string $factoryClass
     * @see Container::make()
     */
    public function enableFactory(string $factoryClass): void
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
    public function configure(string $definition, array $config): void
    {
        $this->configMap[$definition] = $config;
    }

    /**
     * Sets alias definition between definitions.
     *
     * @param string $definition
     * @param string $withDefinition
     */
    public function alias(string $definition, string $withDefinition): void
    {
        $this->aliasMap[$definition] = $withDefinition;
    }

    /**
     * Resolve blueprint of the class for injection.
     *
     * Resolved blueprints are cached in the static variable
     * cache in this function to improve resolution performance.
     *
     * @param string $class
     * @return array
     * @throws \ReflectionException
     */
    protected function resolveBlueprint(string $class): array
    {
        if (!empty($this->blueprintCache[$class])) {
            return $this->blueprintCache[$class];
        }

        $reflection = new \ReflectionClass($class);

        $constructor = $reflection->getConstructor();
        $parameters = [];
        if ($constructor !== null) {
            foreach ($constructor->getParameters() as $param) {
                $hasDefault = $param->isDefaultValueAvailable();
                $parameters[] = [
                    'name' => $param->getName(),
                    'inject' => $this->readAttribute($param, Inject::class),
                    'config' => $this->readAttribute($param, Config::class),
                    'type' => $this->resolvableTypeName($param->getType()),
                    'hasDefault' => $hasDefault,
                    'default' => $hasDefault ? $param->getDefaultValue() : null,
                ];
            }
        }

        return $this->blueprintCache[$class] = [
            'reflection' => $reflection,
            'constructor' => $constructor !== null,
            'parameters' => $parameters,
        ];
    }

    /**
     * Reads the first instance of an attribute from a reflector, or null.
     *
     * @param \ReflectionProperty|\ReflectionParameter $reflector
     * @param string $attributeClass
     * @return object|null
     */
    protected function readAttribute(
        \ReflectionProperty|\ReflectionParameter $reflector,
        string $attributeClass,
    ): ?object {
        $attributes = $reflector->getAttributes($attributeClass);

        return empty($attributes) ? null : $attributes[0]->newInstance();
    }

    /**
     * Returns the type name if it is an autowirable class or interface, otherwise null.
     *
     * @param \ReflectionType|null $type
     * @return string|null
     */
    protected function resolvableTypeName(?\ReflectionType $type): ?string
    {
        if (!$type instanceof \ReflectionNamedType) {
            return null;
        }

        $name = $type->getName();

        return class_exists($name) || interface_exists($name) ? $name : null;
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
    protected function makeFromBlueprint(string $class, array $args): object
    {
        if (!empty($this->resolving[$class])) {
            throw new CircularDependencyException($class, array_keys($this->resolving));
        }

        $this->resolving[$class] = true;

        try {
            return $this->buildFromBlueprint($class, $args);
        } finally {
            unset($this->resolving[$class]);
        }
    }

    /**
     * Instantiates and wires a class from its resolved blueprint.
     *
     * @param string $class
     * @param $args
     * @return object
     *
     * @throws ConfigNotSpecifiedException
     * @throws \ReflectionException
     */
    protected function buildFromBlueprint(string $class, array $args): object
    {
        $blueprint = $this->resolveBlueprint($class);

        /** @var \ReflectionClass $reflection */
        $reflection = $blueprint['reflection'];

        $instance = $reflection->newInstanceWithoutConstructor();

        if ($instance instanceof SharedInstanceInterface) {
            $this->share($instance);
        }

        if ($instance instanceof ConfigurableInterface) {
            if (empty($this->configMap[$class])) {
                throw new ConfigNotSpecifiedException($class);
            }

            $instance->configure($this->configMap[$class]);
        }

        if ($blueprint['constructor']) {
            $instance->__construct(...$this->resolveParameters($class, $blueprint['parameters'], $args));
        }

        return $instance;
    }

    /**
     * Resolves constructor arguments in declaration order.
     *
     * For each parameter, resolution is: name override -> Inject attribute ->
     * Config attribute -> autowire by type -> default value -> error.
     *
     * @param string $class
     * @param array $parameters Parameter blueprints.
     * @param array $args Associative overrides keyed by parameter name.
     * @return array Positional argument list.
     * @throws UnresolvedParameterException
     * @throws \ReflectionException
     * @throws ConfigNotSpecifiedException
     */
    protected function resolveParameters(string $class, array $parameters, array $args): array
    {
        $resolved = [];

        foreach ($parameters as $param) {
            $name = $param['name'];

            if (array_key_exists($name, $args)) {
                $resolved[] = $args[$name];
            } elseif ($param['inject'] !== null) {
                $resolved[] = $this->make($param['inject']->definition ?? $param['type']);
            } elseif ($param['config'] !== null) {
                $resolved[] = $this->resolveConfigValue($class, $param['config']);
            } elseif ($param['type'] !== null) {
                $resolved[] = $this->make($param['type']);
            } elseif ($param['hasDefault']) {
                $resolved[] = $param['default'];
            } else {
                throw new UnresolvedParameterException($class, $name);
            }
        }

        return $resolved;
    }

    /**
     * Resolves a Config attribute value through the override then global layers.
     *
     * @param string $class
     * @param Config $config
     * @return mixed Per-class override, else global value, else the attribute default.
     */
    protected function resolveConfigValue(string $class, Config $config): mixed
    {
        $miss = $this->configMiss;

        $value = Value::get($config->key, $this->configMap[$class] ?? [], $miss);
        if ($value !== $miss) {
            return $value;
        }

        $value = Value::get($config->key, $this->globalConfig, $miss);
        if ($value !== $miss) {
            return $value;
        }

        return $config->default;
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
    protected function makeUsingFactory(string $definition, array $args): mixed
    {
        $factoryClass = $this->factoryMap[$definition];

        if (!$this->enabledFactories[$factoryClass]) {
            return $this->makeFromBlueprint($definition, $args);
        }

        /** @var FactoryInterface $factory */
        $factory = $this->make($factoryClass);

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
    protected function resolveAlias(string $definition): string
    {
        if (empty($this->aliasMap[$definition])) {
            return $definition;
        }

        $resolved = $this->aliasMap[$definition];

        return empty($this->aliasMap[$resolved]) ? $resolved : $this->resolveAlias($resolved);
    }
}
