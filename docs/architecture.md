# Architecture

RestFn is built from a few small parts that each do one job. This page explains
what they are and how a request flows through them.

## The big picture

A request flows through these layers, top to bottom:

```
WebApp          bootstraps everything and runs the request
  Runner        parses the request, runs middleware, returns the response
    Parser      validates and evaluates the operation tree
      Ops        the operations (get, map, run, ...) that do the work
        Actions  your code, called by the run/list operations
```

The [dependency injection container](di.md) creates and wires all of it together.
State that belongs to a single request lives in a `Context` that's passed along the
way.

## The parts

### Container

The container creates objects and injects their dependencies. Every part of RestFn
is resolved from it, which is what makes everything swappable. You declare what you
need in a constructor and the container fills it in. You give it the configuration
and the interface-to-implementation bindings (aliases) when it's built. See
[Dependency Injection](di.md).

### WebApp

`WebApp` is the entry point. `WebApp::createDefault()` takes your config, merges it
over the default bindings, builds the container, resolves the application, and runs
it. The application itself is small: it gets the `Runner` injected and runs it. You
can replace the whole application by binding `ApplicationInterface` to your own
class.

### Runner

The `Runner` drives one request. It asks the request parser for the request, wraps
the handling in your middleware, validates and evaluates the request body, and
passes the result to the response. See [Runner](runner.md).

### Parser and operations

The `Parser` is the evaluator. It takes an operation tree and walks it. Each node is
an operation: the first element of the array is the operation name, and the rest are
its parameters. The parser looks up the operation, validates it, and evaluates it.
Operations can contain other operations, so the parser is recursive. See
[Operations](ops/index.md).

There are two passes. Validate checks the whole tree before anything runs and
returns errors if the request is malformed. Evaluate runs the tree and produces the
result.

### Context

The `Context` holds the state for a single request: the variables set by the `var`
operation, and the current nesting depth that guards against overly deep requests.
The parser itself is stateless, so the same parser and operations are reused for the
whole request. Nothing leaks between requests because each request gets its own
`Context`.

### Actions

Actions are your code. An action implements `ActionInterface` (or
`ListActionInterface` for paginated lists) and is called by the `run` (or `list`)
operation by name. This is where you talk to your database, call other services, and
do the real work. See [Actions](actions.md).

### Request and Response

The `Runner` reads the request through a `RequestParserInterface`. The default
`JsonRequestParser` reads the JSON body. The result is turned into output by a
`ResponseInterface`, and the default `JsonResponse` encodes it as JSON. Both are
swappable.

### Authentication

Authentication is a set of services and a middleware. The middleware reads the
bearer token, verifies it, resolves an identity, and stores it. Operations that run
actions check whether an action needs authentication. See
[Authentication](authentication.md).

## Request lifecycle

Putting it together, a single request goes like this:

1. The web server calls your `index.php`, which calls `WebApp::createDefault()`.
2. The `Runner` reads the request body and turns it into a `Request`.
3. Middleware run on the way in. By default error handling wraps everything, and
   authentication establishes the identity from a bearer token.
4. The parser validates the operation tree. If it's invalid, the request is
   rejected.
5. The parser evaluates the tree. Operations call your actions as needed.
6. Middleware run on the way out.
7. The result is turned into a response (JSON by default) and returned. If anything
   threw along the way, the error middleware turns it into a JSON error instead.

## Everything is swappable

Each piece sits behind an interface and is bound to a default in
`WebApp::DEFAULT_ALIASES`. To replace one, bind its interface to your own class when
you call `createDefault()`:

```php
WebApp::createDefault([
    'aliases' => [
        ResponseInterface::class => App\XmlResponse::class,
    ],
    'config' => ['global' => [/* ... */]],
])->run();
```

The same goes for the request parser, the evaluator, the response, the
authenticator, the token parser, and the application itself.
