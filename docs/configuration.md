# Configuration

Everything is configured through the array you pass to `WebApp::createDefault()` (or
to the container directly). This page is the reference for every value RestFn reads.
For *how* configuration is injected and resolved, see [Dependency Injection](di.md).

## Shape

```php
WebApp::createDefault([
    'config' => [
        'global'    => [/* shared settings, grouped by concern */],
        'overrides' => [/* per-class settings, keyed by class name */],
    ],
    'aliases'   => [/* interface => implementation */],
    'factories' => [/* class => factory class */],
]);
```

Almost everything goes under `config.global`, which every class reads from. Use
`config.overrides[SomeClass::class]` only when one specific class needs a different
value than the global one. A class resolves each value as override, then global, then
the built-in default.

`aliases` and `factories` sit at the top level, next to `config`, not inside it.
`aliases` bind an interface to the class that implements it; see
[`WebApp::DEFAULT_ALIASES`](architecture.md#everything-is-swappable). `factories` bind
a class to a factory that builds it.

## Operations and actions

| Key           | Default          | Description                                                                                        |
| ------------- | ---------------- | -------------------------------------------------------------------------------------------------- |
| `ops`         | all built-in ops | Map of operation name to class that a request may use. See [Operations](ops/index.md).             |
| `actions`     | `[]`             | Map of action name to class the [`run`](ops/run.md) operation may call. See [Actions](actions.md). |
| `listActions` | `[]`             | Map of action name to class the [`list`](ops/list.md) operation may call.                          |

`createDefault()` fills `ops` with every built-in operation (`WebApp::DEFAULT_OPS`)
when you don't set it. Set it to a narrower map to restrict what a client can use; it
becomes an allow list.

```php
'global' => [
    'ops'         => ['get' => GetOp::class, 'run' => RunOp::class /* ... */],
    'actions'     => ['getUser' => App\Actions\GetUserAction::class],
    'listActions' => ['users'   => App\Actions\ListUsersAction::class],
],
```

## Limits (hardening)

A request is a client-supplied expression tree, so these limits bound how much work
one request can ask for. The defaults are safe. Lower them if your clients have no
real need for deep or large expressions.

| Key                            | Default | Description                                                                                                                                        |
| ------------------------------ | ------- | -------------------------------------------------------------------------------------------------------------------------------------------------- |
| `limits.maxDepth`              | `64`    | Maximum nesting depth of an expression. Guards against stack exhaustion from deeply nested input. Exceeding it raises `MaxDepthExceededException`. |
| `limits.maxSequenceOperations` | `64`    | Maximum number of items a single [`sequence`](ops/sequence.md) operation may contain. Guards against a runaway sequence.                           |

```php
'global' => [
    'limits' => [
        'maxDepth'              => 32,
        'maxSequenceOperations' => 50,
    ],
],
```

Both are checked during validation, so an over-limit request is rejected before
anything runs.

## Runner

| Key                  | Default       | Description                                                                                                                                                 |
| -------------------- | ------------- | ----------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `runner.middleware`  | default stack | Ordered list of middleware classes, outermost first. See [Middleware](middleware.md).                                                                       |
| `runner.debug`       | `false`       | When `true`, error responses include the real message and a stack trace. Keep it `false` in production. See [Error handling](error-handling.md). |
| `runner.inputStream` | `php://input` | Stream the request body is read from. Handy to point elsewhere in tests. See [Runner](runner.md).                                                           |

`createDefault()` sets `runner.middleware` to `WebApp::DEFAULT_MIDDLEWARE`
(`ErrorMiddleware` then `AuthenticationMiddleware`) when you don't set it. Setting it
yourself replaces the whole stack, so include those two if you still want them.

## Authentication

Read by the authentication services. See [Authentication](authentication.md) for the
full picture.

| Key                     | Default         | Description                                                                                            |
| ----------------------- | --------------- | ------------------------------------------------------------------------------------------------------ |
| `auth.jwt.secret`       | `''`            | Secret used to verify the JWT. **Required** for authentication; must be at least 32 bytes for `HS256`. |
| `auth.jwt.algorithm`    | `HS256`         | Algorithm the token is verified with. Pinned to prevent algorithm-confusion attacks.                   |
| `auth.header`           | `Authorization` | Request header the token is read from.                                                                 |
| `auth.scheme`           | `Bearer`        | Scheme prefix expected before the token.                                                               |
| `auth.identity.idClaim` | `sub`           | Token claim used as the identity's id.                                                                 |
| `auth.identity.claims`  | `[]`            | Extra claims to copy from the token onto the identity's data.                                          |

```php
'global' => [
    'auth' => [
        'jwt'      => ['secret' => getenv('JWT_SECRET')],
        'identity' => ['idClaim' => 'sub', 'claims' => ['role', 'email']],
    ],
],
```

## A full example

```php
use ArekX\RestFn\App\WebApp;

echo WebApp::createDefault([
    'config' => [
        'global' => [
            'actions' => ['getUser' => App\Actions\GetUserAction::class],
            'limits'  => ['maxDepth' => 32],
            'runner'  => ['debug' => getenv('APP_ENV') === 'local'],
            'auth'    => ['jwt' => ['secret' => getenv('JWT_SECRET')]],
        ],
    ],
])->run();
```
