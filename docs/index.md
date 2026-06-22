# RestFn

RestFn ("REST function") is a library for building a single, **functional-style
endpoint**.

Instead of many URLs, the client sends one request to one endpoint. The request is
a tree of operations. The server validates that tree, runs it, and returns the
result. You write your own logic as *actions*, and the client composes those
actions with built-in operations like `get`, `map`, `sort`, `ifElse` and
`sequence` to get back the data it needs in a single request.

RestFn gives you the engine for this: an [operation language](ops/index.md), a
request [runner](runner.md), [authentication](authentication.md), and a [dependency
injection container](di.md) that wires it together. You provide the
[actions](actions.md), which are your own code, and you decide which operations the
client is allowed to use.

## Requirements

This library requires **PHP 8.4+** with the `json` and `mbstring` extensions loaded.

## Installation

Require it in your project with Composer:

```bash
composer require arekx/restfn
```

## A first look

Your whole API is one PHP file:

```php
use ArekX\RestFn\App\WebApp;

echo WebApp::createDefault([
    'config' => [
        'global' => [
            // Actions the run operation can call. All operations are available by default.
            'actions' => ['getUser' => App\Actions\GetUserAction::class],
        ],
    ],
])->run();
```

A client then sends an operation tree as the JSON body:

```json
["get", "email", ["run", "getUser", 1]]
```

`run` calls your `getUser` action with `1`, `get` pulls out the `email` field, and
the response is the email. Read [Getting Started](getting-started.md) for the full
walkthrough.

## Documentation

- [Getting Started](getting-started.md) - build your first endpoint.
- [Architecture](architecture.md) - how the pieces fit together.
- [Operations](ops/index.md) - the operation language.
- [Cookbook](cookbook.md) - recipes that compose operations for common tasks.
- [Actions](actions.md) - defining your own actions.
- [Authentication](authentication.md) - protecting actions with tokens.
- [Runner](runner.md) and [Middleware](middleware.md) - the request lifecycle.
- [Dependency Injection](di.md) - how wiring works.
- [Configuration](configuration.md) - every config value and its default.
