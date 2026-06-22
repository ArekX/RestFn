# RestFn

RestFn ("REST function") is a library for building a **single functional endpoint**.

Instead of many URLs, a client sends one composable expression — a tree of
operations — to one endpoint. The server validates it, evaluates it, and returns
the shaped result. You expose your own logic as *actions*, and the client composes
those actions (and built-in operations like `get`, `map`, `sort`, `ifElse`,
`sequence`) to fetch and reshape exactly the data it needs in a single request.

RestFn provides the engine: an [operation language](ops/index.md) and a
[dependency injection container](di.md). You wire it into your endpoint and decide
how requests come in, how responses go out, and how authentication and
authorization are enforced (typically inside your actions).

## Requirements

This library requires **PHP 8.4+** with the `json` and `mbstring` extensions loaded.

## Installation

Require it in your project with Composer:

```bash
composer require arekx/restfn
```

## A first look

```php
use ArekX\RestFn\DI\Container;
use ArekX\RestFn\Parser\Context;
use ArekX\RestFn\Parser\Parser;
use ArekX\RestFn\Parser\Ops\GetOp;
use ArekX\RestFn\Parser\Ops\RunOp;
use ArekX\RestFn\Parser\Ops\ValueOp;

// Configure the container once: register the operations and any actions.
$container = new Container([
    'config' => [
        'overrides' => [
            Parser::class => ['ops' => [
                ValueOp::class,
                GetOp::class,
                RunOp::class,
                // ...register the operations you want to allow
            ]],
            RunOp::class => ['actions' => [
                'getUser' => \App\Actions\GetUserAction::class,
            ]],
        ],
    ],
]);

$parser = $container->make(Parser::class);

// Per request: validate the client program, then evaluate it.
$program = ['get', 'email', ['run', 'getUser', 1]];
$context = new Context();

if (($errors = $parser->validate($program, $context)) !== null) {
    // reject the request with $errors
}

$result = $parser->evaluate($program, $context); // the user's email
```

See the [operation reference](ops/index.md) for the full language and the
[dependency injection guide](di.md) for how wiring works.
