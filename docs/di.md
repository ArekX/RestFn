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
$instance = $injector->make(MyClass::class);
// Do something with your instance.
```

WIP

### Auto-wiring

WIP

### Shared instances (Singletons)

WIP

### Factories

WIP

### Injector is NOT a service locator

In most cases RestFn handles all injection for you. However you can
request na injector by specifying the injector as a public property.

Do **not** use injector for loading services directly. Service locator
is an anti-pattern and you should only use the injector directly
when you need to resolve or map classes manually through some logic.