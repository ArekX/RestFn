# Dependency Injection

RestFn is using a custom Dependency Injection (DI) system where 
you inject dependencies by specifying them as public properties 
of your file.

DI system handles all auto-wiring and sending configuration to your
classes by looking at the class metadata.

## Usage

container is initialized by specifying:

```php
$container = new \ArekX\RestFn\DI\container();
```

In order to initalize a class by using an container you need to call
`make()` function.

```php
$instance = $container->make(\MyClass::class);
// Do something with your instance.
```

Calling function `make()` creates new instance of the desired class, processes auto-wiring 
and shares the class if necessary. You can see below for more info on specific parts of injection process.

## Auto-wiring

Auto-wiring is a process of instantiating and loading all of the necessary dependencies which
one class needs automatically without it being manually created. This means that if you have
a class called `Class1` which needs `Class2` to function, you would usually first create `Class2`
and then pass it to `Class1`.

Example:
```php
class Class2 { }
class Class1 { 
    public function __construct(Class2 $class2) {
        // do something with $class2
    }
}

$class2 = new Class2();
$class1 = new Class1($class2);
```

Auto-wiring handles this for you automatically so you would not need to do this manually.

This DI system only auto-wires classes which implement `\ArekX\RestFn\DI\Contracts\Injectable`
interface. This is due to the nature of how this auto-wiring works. Other DI systems usually
inject dependencies into the constructor itself because that is the best place available for them
to ensure nothing can be done before they are available.

This DI system does not inject dependencies into a constructor. This system uses a feature of
PHP 7.4 called property types. When you set a property type of a class this DI system resolves
it to a valid dependency class, calls `container::make()` to make that dependency or load a shared
instance if needed and wires it in.

These dependencies are injected in public properties before the `__construct()` is called so you
will get the flexibility of having constructor arguments while making sure everything is auto-wired
properly before your class can do any work.

By Implementing `Injectable` interface you ensure that only classes which need to have this
functionality will actually be auto-wired.

Full Example:
```php
class Class2 {
    public function test() {
        return "Auto-wiring works!";
    }
}

class Class1 implements \ArekX\RestFn\DI\Contracts\Injectable {
    public Class2 $class2;

    public function __construct($arg1) {
        echo $this->class2->test(); // Outputs: Auto-wiring works!
        echo $arg1; // Outputs: valuePassedToArg1
    }
}

$container = new \ArekX\RestFn\DI\container();
$class1 = $container->make(Class1::class, 'valuePassedToArg1');
```

container first creates the instance of this class, wires the dependencies then calls `__construct()`
on the created the class, passing any arguments necessary.

## Shared instances (Singletons)

Instances can be shared on the container across all calls to `container::make()` which 
includes time when you are auto-wiring dependencies. These classes are instantiated only once and their reference is 
shared across all subsequent calls to `container::make()`.

### Sharing as an instance

You can share an instance by calling:

```php
$instance = new \MyClass();
$container->share($instance);


$shared = $container->make(\MyClass::class);

echo $shared === $instance ? 'Same classes' : 'Not same'; // Will output: Same classes
```

This class will be automatically shared across all calls to `container::make()`

### Sharing as a class name

If you share a class as string (by calling `MyClass::class` or passing a string), this function 
will first instantiate this class by calling the `container::make()` function before making this class shared.

```php
$container->share(\MyClass::class);


$sharedA = $container->make(\MyClass::class);
$sharedB = $container->make(\MyClass::class);

echo $sharedA === $sharedB ? 'Same classes' : 'Not same'; // Will output: Same classes
```

### Sharing by using an interface

If your class implements `\ArekX\RestFn\DI\Contracts\SharedInstance` your class will always 
automatically be defined as a shared class and instantiated only once. Using this method is 
useful when you want to create your own services such as `Database` service or other api 
services which need to be only instantiated once.

```php
class MyClass implements \ArekX\RestFn\DI\Contracts\SharedInstance {}

$sharedA = $container->make(\MyClass::class);
$sharedB = $container->make(\MyClass::class);

echo $sharedA === $sharedB ? 'Same classes' : 'Not same'; // Will output: Same classes
``` 

## Configurable Instances

Instances can have configurations passed to them by the container itself. In order for
instances to get the configuration passed to them they need to implement `ArekX\RestFn\DI\Contracts\Configurable`
interface.

To these interfaces, container will pass the array config to them before their `__construct()` is called. This is done in a 
way to ensure that the class you instantiate has everything ready for it before it can do any work.

Configuration is passed per class in an array during container creation or by a call to `container::configure()`:

```php
$container = new \ArekX\RestFn\DI\container([
    'configurations' => [
         \MyConfigurableClass::class => [
                'key1' => 'value',
                'key2' => 'value2',
            ]
    ]
]);

class MyConfigurableClass implements ArekX\RestFn\DI\Contracts\Configurable {
    public function configure(array $config) {
        /**
          * $config here will contain 
          * [
          *    'key1' => 'value',
          *    'key2' => 'value2',
          * ] 
          */
    }
    
    public function __construct($arg1, $arg2) {
        // This is called after configure in this case, so all dependencies
        // and configuration is available here.
    }
}

$instance = $container->make(MyConfigurableClass::class);
```

If there is no configuration specified for the instance in the container itself. container will throw an error.

You can also manually pass the configuration for a specific class by calling `container::configure()`.

## Aliasing

Classes can be aliased so that you can set an interface and inject the implementation of that interface
to every class which implements `Injectable` interface.

Aliasing can be setup by calling `container::alias()` or by passing the constructor configuration.

```php
interface MyInterface {}

class MyClass implements MyInterface {}

class MyClass2 implements \ArekX\RestFn\DI\Contracts\Injectable {
    public MyInterface $interface;
}

$container = new \ArekX\RestFn\DI\container([
    'aliases' => [
        \MyInterface::class => \MyClass::class
    ]
]);

$instance = $container->make(MyClass2::class); // Creates instance of MyClass2 with MyClass injected into $interface.
```

## Factories

Factory classes are classes to which container delegates instance creation. When a `make()` function is called,
container first checks if there are factory classes set for that specific class and if there are it instantiates
the factory classes using `make()` method and then calls the factory's `create()` method, passing the desired
class and arguments.

For a class to be a factory class it must implement the `\ArekX\RestFn\DI\Contracts\Factory` interface.

Example:
```php
class MyFactory implements \ArekX\RestFn\DI\Contracts\Factory {

  public function create(string $definition,array $args) {
      // Your logic for handling $definitionClass here.
      return new $definition(...$args);
  }
}

class MyClass {}

$container = new \ArekX\RestFn\DI\container([
    'factories' => [
        \MyClass::class => \MyFactory::class
    ]
]);

// Instance will be created by using MyFactory::create() method.
$instance = $container->make(MyClass::class);
```

Please note that instances created through factory's `create()` method will not go through the injection process
which means that they will not be auto-wired or shared by default. If you need this functionality then you
need to inject the container in the factory function.

Example:
```php
class MyFactory implements \ArekX\RestFn\DI\Contracts\Factory {
  
  public \ArekX\RestFn\DI\container $container;

  public function create(string $definition,array $args) {
      // container calls disableFactory() for this class before calling this create() function
      // so its safe to directly call make() here.
      return $this->container->make($definition, ...$args);
  }
}

class MyClass {}

$container = new \ArekX\RestFn\DI\container([
    'factories' => [
        \MyClass::class => \MyFactory::class
    ]
]);

$container->share($this);


// Instance will be created by using MyFactory::create() method.
$instance = $container->make(MyClass::class);
```

## Considerations

### container is NOT a service locator

For most of your use-cases RestFn will handle all injection for you. However you can
request na container by specifying the `container` as a public property in your classes 
which implement `Injectable` interface.

But this container should **never** be used for loading services directly by passing definitions. 

This makes the DI system a Service Locator, which is an anti-pattern and you should only 
use the container directly when  you need to resolve  or map classes manually through some 
logic such as mapping a request value to a specific class, or to handle some specific 
injection use cases.