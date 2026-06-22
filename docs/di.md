# Dependency Injection

RestFn ships a small dependency injection (DI) container. A class declares what it
needs as **constructor parameters**, and the container fills them in: other classes,
configuration values, and shared services, all resolved automatically.

There's one way to wire a class, and that's its constructor. Parameters are
autowired by type, and attributes are only needed when the type alone isn't enough.

## Creating the container

```php
use ArekX\RestFn\DI\Container;

$container = new Container([
    'config' => [
        'global'    => [/* shared configuration, grouped by concern */],
        'overrides' => [/* per-class configuration, keyed by class name */],
    ],
    'aliases'   => [/* interface => implementation */],
    'factories' => [/* class => factory class */],
]);
```

All keys are optional. An empty container is just `new Container()`.

## make() and autowiring

You create instances with `make()`:

```php
$instance = $container->make(MyClass::class);
```

`make()` instantiates the class and resolves each constructor parameter. If a
parameter is type-hinted with another class or interface, the container creates that
too, recursively, so you never wire dependencies by hand.

```php
class Mailer {}

class UserService
{
    public function __construct(
        public Mailer $mailer, // autowired by type
    ) {}
}

$service = $container->make(UserService::class);
// $service->mailer is a Mailer instance, created automatically.
```

Use **promoted constructor properties** to both receive and store a dependency, as
above. That's the style RestFn uses throughout.

### Overriding constructor arguments

Pass an associative array to `make()` to supply specific arguments by parameter name.
Anything you don't provide is autowired:

```php
class Report
{
    public function __construct(
        public Mailer $mailer,  // autowired
        public string $title,   // supplied below
    ) {}
}

$report = $container->make(Report::class, ['title' => 'Monthly report']);
```

Each constructor parameter is resolved in this order:

1. An override passed to `make()` (matched by parameter name).
2. An `#[Inject]` attribute (see below).
3. A `#[Config]` attribute (see below).
4. Autowiring by type, if the parameter is a class or interface.
5. The parameter's default value.
6. Otherwise an `UnresolvedParameterException` is thrown.

## Injecting a specific implementation: `#[Inject]`

Autowiring uses the parameter's declared type. When you need a *specific* class, for
example a concrete implementation of an interface without registering an alias, use
`#[Inject]`:

```php
use ArekX\RestFn\DI\Attributes\Inject;

class ReportService
{
    public function __construct(
        #[Inject(SqlConnection::class)] public ConnectionInterface $connection,
    ) {}
}
```

With no argument, `#[Inject]` just autowires by the parameter's type, the same as no
attribute, so it's mostly useful with an explicit class.

## Injecting configuration values: `#[Config]`

Configuration values can't be resolved from a type alone, so you request them by key
with `#[Config]`. The key is a dot-path into the container's configuration:

```php
use ArekX\RestFn\DI\Attributes\Config;

class Client
{
    public function __construct(
        #[Config('api.baseUrl', default: 'https://example.test')] public string $baseUrl,
    ) {}
}
```

A `#[Config]` value is resolved through three layers, in order:

1. **Per-class override**, from `config.overrides[ThisClass]` at the dot-path.
2. **Global**, from `config.global` at the dot-path.
3. The attribute's `default`.

In practice you put almost all configuration under `global`. Every class reads from
it, and most config keys are read by a single class anyway. Use `overrides` only when
one specific class needs a different value than the global one.

```php
$container = new Container([
    'config' => [
        'global' => [
            'api' => ['baseUrl' => 'https://api.example.com'],
        ],
        'overrides' => [
            // Only Client uses a different base URL:
            Client::class => ['api' => ['baseUrl' => 'https://internal.example.com']],
        ],
    ],
]);

$container->make(Client::class)->baseUrl; // 'https://internal.example.com'
```

Resolution tells "missing" apart from a real value, so a configured `null`, `false`,
or `0` is honored instead of falling through to the default.

`#[Config]` works on any class. It doesn't need a marker interface.

## Aliasing interfaces

To autowire an interface, register which implementation it maps to:

```php
interface LoggerInterface {}
class FileLogger implements LoggerInterface {}

$container = new Container([
    'aliases' => [
        LoggerInterface::class => FileLogger::class,
    ],
]);

class Service
{
    public function __construct(public LoggerInterface $logger) {}
}

$container->make(Service::class)->logger; // a FileLogger
```

You can also add aliases at runtime with `$container->alias($definition, $withDefinition)`.

## Shared instances (singletons)

By default every `make()` call returns a fresh instance. A shared instance is created
once and returned for every call after that.

Share an existing object:

```php
$instance = new Database();
$container->share($instance);

$container->make(Database::class) === $instance; // true
```

Share by class name, where the container creates it and then shares it:

```php
$container->share(Database::class);
$container->make(Database::class) === $container->make(Database::class); // true
```

Or mark a class to always be shared by implementing `SharedInstanceInterface`:

```php
use ArekX\RestFn\DI\Contracts\SharedInstanceInterface;

class Database implements SharedInstanceInterface {}

$container->make(Database::class) === $container->make(Database::class); // true
```

The container also **shares itself**. Injecting `Container` (or the PSR-11
`ContainerInterface`) gives you the same configured container, not a new one.

## Configurable instances

`#[Config]` injects individual values. When a class needs the whole configuration
array, for example to build something from it, implement `ConfigurableInterface`. Its
`configure()` method receives that class's `config.overrides` entry, and runs
*before* the constructor:

```php
use ArekX\RestFn\DI\Contracts\ConfigurableInterface;

class Registry implements ConfigurableInterface
{
    public array $items = [];

    public function configure(array $config): void
    {
        $this->items = $config['items'] ?? [];
    }
}

$container = new Container([
    'config' => [
        'overrides' => [
            Registry::class => ['items' => ['a', 'b']],
        ],
    ],
]);
```

If a `ConfigurableInterface` class has no `overrides` entry, the container throws
`ConfigNotSpecifiedException`. You can also set it at runtime with
`$container->configure(SomeClass::class, [...])`.

## Factories

A factory takes over creation of a class. Register one and the container hands
`make()` off to the factory's `create()` method:

```php
use ArekX\RestFn\DI\Contracts\FactoryInterface;

class WidgetFactory implements FactoryInterface
{
    public function create(string $definition, array $args): mixed
    {
        return new $definition(...$args);
    }
}

$container = new Container([
    'factories' => [
        Widget::class => WidgetFactory::class,
    ],
]);

$container->make(Widget::class); // created via WidgetFactory::create()
```

The factory itself is created through the container, so it can declare its own
dependencies. Instances returned from `create()` don't go through autowiring. If you
want that, inject the container into the factory and call `make()` yourself:

```php
class WidgetFactory implements FactoryInterface
{
    public function __construct(public Container $container) {}

    public function create(string $definition, array $args): mixed
    {
        // The container disables this factory while create() runs,
        // so calling make() here won't recurse back into it.
        return $this->container->make($definition, $args);
    }
}
```

## Circular dependencies

If two classes depend on each other (`A` needs `B`, `B` needs `A`), the container
catches the cycle while resolving and throws `CircularDependencyException` instead of
recursing until the stack is exhausted.

## A note on the container as a service locator

You *can* inject the `Container` and call `make()` from inside a class, and the
factory pattern above relies on that. But reaching for the container to pull
arbitrary services on demand turns it into a service locator, which hides a class's
real dependencies. Prefer declaring dependencies as constructor parameters. Use the
container directly only when creation really depends on runtime data, like mapping a
request value to a class to instantiate.
