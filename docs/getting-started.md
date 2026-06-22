# Getting Started

RestFn runs your whole API through one endpoint. The client sends one JSON request,
which is a tree of operations. The server validates it, runs it, and sends back the
result. This page takes you from an empty project to a working endpoint.

## Requirements

You need PHP 8.4+ with the `json` and `mbstring` extensions loaded.

## Installation

Add RestFn to your project with Composer:

```bash
composer require arekx/restfn
```

## Your first endpoint

Everything goes through a single PHP file. Point your web server at it and you have
an API. It's one call to `WebApp::createDefault()`:

```php
<?php
// public/index.php

require __DIR__ . '/../vendor/autoload.php';

use ArekX\RestFn\App\WebApp;
use App\Actions\GetUserAction;

echo WebApp::createDefault([
    'config' => [
        'global' => [
            // Actions the run operation can call.
            'actions' => [
                'getUser' => GetUserAction::class,
            ],
        ],
    ],
])->run();
```

`createDefault()` builds the container and wires up the defaults: request parsing,
JSON response, the evaluator, authentication services, error handling, and all
built-in operations. It returns the application. You call `run()` on it, which reads
the request body, runs it, and returns the JSON response. You echo that.

Configuration normally goes under `global`. Every class reads its settings from
there. You only use `overrides` when one specific class needs a different value.

## Errors and debug mode

Errors don't escape as an uncaught exception. The default error middleware catches
anything that goes wrong (a malformed body, a failed validation, an action that
throws) and returns it as a JSON error.

While you develop, turn on debug mode so those errors carry the real message and a
stack trace. Keep it off in production, where errors are reduced to a generic
message so nothing leaks:

```php
echo WebApp::createDefault([
    'config' => [
        'global' => [
            'runner'  => ['debug' => getenv('APP_ENV') === 'local'],
            'actions' => [/* ... */],
        ],
    ],
])->run();
```

See [Error handling](middleware.md#error-handling) for the details.

## Registering operations

Operations are the building blocks a client can use. All built-in operations are
available by default, so you don't have to register them to get started.

To restrict what a client can use, set the `ops` config value to a map of operation
name to class. That map becomes the allow list: only those operations can be used.
The key is the name, which is what a request uses to call the operation:

```php
'global' => ['ops' => [
    'value' => ValueOp::class,
    'get'   => GetOp::class,
    'map'   => MapOp::class,
    'sort'  => SortOp::class,
    'run'   => RunOp::class,
]],
```

See the [operation reference](ops/index.md) for the full list.

## Registering actions

An action is your code. It is a class that does some work and returns data. The
`run` operation calls actions by name, so you register the names under `actions`:

```php
'global' => ['actions' => [
    'getUser'    => App\Actions\GetUserAction::class,
    'createUser' => App\Actions\CreateUserAction::class,
]],
```

A simple action looks like this:

```php
use ArekX\RestFn\Parser\Contracts\ActionInterface;

class GetUserAction implements ActionInterface
{
    public function run(mixed $data): array
    {
        // $data is whatever the client passed to `run`.
        return ['id' => $data, 'email' => 'user@example.com'];
    }
}
```

See [actions](actions.md) for the details.

## Sending a request

The client sends the operation tree as the JSON body of a request to your single
endpoint. To get a user's email:

```json
["get", "email", ["run", "getUser", 1]]
```

This reads inside out. `run` calls the `getUser` action with `1`, and `get` pulls
the `email` field out of the result. The response is:

```json
"user@example.com"
```

Because everything is one request, the client can also do several things at once.
This creates a user and reads back its id in one call:

```json
["sequence",
  ["var", "user", ["run", "createUser", ["value", {"email": "user@example.com"}]]],
  ["get", "id", ["var", "user"]]
]
```

## Where to go next

- [Architecture](architecture.md) - how the pieces fit together.
- [Operations](ops/index.md) - the full list of operations.
- [Cookbook](cookbook.md) - recipes for common tasks.
- [Actions](actions.md) - how to define your own actions.
- [Authentication](authentication.md) - protecting actions with tokens.
- [Runner](runner.md) and [Middleware](middleware.md) - the request lifecycle.
- [Configuration](configuration.md) - every config value and its default.
