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

## Writing an action

An action is your code. It's a class with a `run` method, and the `run` operation
calls it by name. Actions are built by the container, so they can ask for whatever
they need in the constructor. This one loads a user with a Doctrine DBAL connection:

```php
use ArekX\RestFn\Parser\Contracts\ActionInterface;
use Doctrine\DBAL\Connection;

class GetUserAction implements ActionInterface
{
    public function __construct(
        protected Connection $db,
    ) {}

    public function run(mixed $data): array
    {
        // $data is whatever the client passed to `run`.
        return $this->db
            ->executeQuery('select id, email from users where id = ?', [$data])
            ->fetchAssociative() ?: [];
    }
}
```

The container injects that `Connection`. Since a DBAL connection is built a specific
way, you hand the container a small factory for it:

```php
use ArekX\RestFn\DI\Contracts\FactoryInterface;
use Doctrine\DBAL\DriverManager;

class ConnectionFactory implements FactoryInterface
{
    public function create(string $definition, array $args): mixed
    {
        return DriverManager::getConnection(['url' => getenv('DATABASE_URL')]);
    }
}
```

Register the action by name and point the container at the factory:

```php
WebApp::createDefault([
    'factories' => [
        Connection::class => App\ConnectionFactory::class,
    ],
    'config' => [
        'global' => ['actions' => ['getUser' => App\Actions\GetUserAction::class]],
    ],
])->run();
```

That's the whole loop: register an action, ask for what it needs, and the container
builds it. See [Actions](actions.md) and [Dependency Injection](di.md) for more.

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

All built-in operations are available by default, so there's nothing to register to
start composing requests like this.

## Where to go next

- [Architecture](architecture.md) - how the pieces fit together.
- [Operations](ops/index.md) - the full list of operations.
- [Cookbook](cookbook.md) - recipes for common tasks.
- [Actions](actions.md) - how to define your own actions.
- [Authentication](authentication.md) - protecting actions with tokens.
- [Runner](runner.md) and [Middleware](middleware.md) - the request lifecycle.
- [Error Handling](error-handling.md) - how errors come back as JSON.
- [Dependency Injection](di.md) - defining services and wiring them in.
- [Configuration](configuration.md) - every config value and its default.
