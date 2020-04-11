# Dependency Injection

RestFn is using a custom Dependency Injection (DI) system where 
you inject dependencies by specifying them as public properties 
of your file.

DI system handles all auto-wiring and sending configuration to your
classes by looking at the class metadata.

## Usage

Injector is initialized by specifying:

```php
$injector = new \ArekX\RestFn\DI\Injector();
```

In order to initalize a class by using an injector you need to call
`make()` function.

```php
$instance = $injector->make(\MyClass::class);
// Do something with your instance.
```

WIP

## Injection definition

WIP

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
it to a valid dependency class, calls `Injector::make()` to make that dependency or load a shared
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

class Class1 {
    public Class2 $class2;

    public function __construct($arg1) {
        echo $this->class2->test(); // Outputs: Auto-wiring works!
        echo $arg1; // Outputs: valuePassedToArg1
    }
}

$injector = new \ArekX\RestFn\DI\Injector();
$class1 = $injector->make(Class1::class, 'valuePassedToArg1');
```

Injector first creates the instance of this class, wires the dependencies then calls `__construct()`
on the created the class, passing any arguments necessary.

## Shared instances (Singletons)

Instances can be shared on the Injector across all calls to `Injector::make()` which 
includes time when you are auto-wiring dependencies. These classes are instantiated only once and their reference is 
shared across all subsequent calls to `Injector::make()`.

### Sharing as an instance

You can share an instance by calling:

```php
$instance = new \MyClass();
$injector->share($instance);


$shared = $injector->make(\MyClass::class);

echo $shared === $instance ? 'Same classes' : 'Not same'; // Will output: Same classes
```

This class will be automatically shared across all calls to `Injector::make()`

### Sharing as a defintion

If you share a class as an non-object ([as a definition](#injection-definition)), this 
function will first instantiate this class by calling the `Injector::make()` function 
before making this class shared.

```php
$injector->share(\MyClass::class);


$sharedA = $injector->make(\MyClass::class);
$sharedB = $injector->make(\MyClass::class);

echo $sharedA === $sharedB ? 'Same classes' : 'Not same'; // Will output: Same classes
```

### Sharing by using an interface

If your class implements `\ArekX\RestFn\DI\Contracts\SharedInstance` your class will always 
automatically be defined as a shared class and instantiated only once. Using this method is 
useful when you want to create your own services such as `Database` service or other api 
services which need to be only instantiated once.

```php
class MyClass implements \ArekX\RestFn\DI\Contracts\SharedInstance {}

$sharedA = $injector->make(\MyClass::class);
$sharedB = $injector->make(\MyClass::class);

echo $sharedA === $sharedB ? 'Same classes' : 'Not same'; // Will output: Same classes
``` 


## Factories

WIP

## Considerations

### Injector is NOT a service locator

For most of your use-cases RestFn will handle all injection for you. However you can
request na injector by specifying the `Injector` as a public property in your classes 
which implement `Injectable` interface.

But this injector should **never** be used for loading services directly by passing definitions. 

This makes the DI system a Service Locator, which is an anti-pattern and you should only 
use the injector directly when  you need to resolve  or map classes manually through some 
logic such as mapping a request value to a specific class, or to handle some specific 
injection use cases.