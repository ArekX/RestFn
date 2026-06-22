# Error Handling

Errors never escape as an uncaught exception. Whatever goes wrong while handling a
request (a malformed body, a failed validation, a missing token, a bug in an action)
comes back to the client as a JSON error. This page explains how that works and how
you control what the client sees.

## What catches errors

The work is done by `ErrorMiddleware`. `WebApp::createDefault()` puts it at the top of
the default middleware stack, so it's the outermost layer around the whole request. It
wraps the rest of the pipeline in a `try`/`catch`, and turns anything thrown below it
into a response instead of letting it bubble out.

The request body is parsed before the middleware run, so a body that isn't valid JSON
would normally fail before the error middleware could see it. The runner handles that
by deferring the parse failure into the pipeline, so the error middleware catches it
like any other error. The upshot is that your `index.php` stays a single line and you
don't catch anything yourself:

```php
echo WebApp::createDefault($config)->run();
```

## The response shape

An error response is a JSON object with an `error` message. It may also carry
`details` (extra, client-safe information) and, in debug mode, a `debug` block:

```json
{
    "error": "Request validation failed.",
    "details": ["get", { "0": "missing key" }]
}
```

## Client errors and internal errors

What ends up in `error` depends on whether the exception is meant for the client.

- **Client errors** describe something the caller can fix: a malformed body, failed
  validation, a missing or invalid token. These implement `ClientExceptionInterface`,
  and they always show their real message plus any `details`.
- **Internal errors** are everything else: a bug in an action, a failing database
  call, an unexpected fault. Outside debug mode these are hidden behind a generic
  `"An unexpected error occurred."` so no implementation detail leaks.

These built-in exceptions are client errors:

| Exception                         | Raised when                                  | `details`              |
| --------------------------------- | -------------------------------------------- | ---------------------- |
| `InvalidRequestException`         | the body is malformed or fails validation    | the validation errors  |
| `InvalidOperation`                | the request uses an unknown operation        | none                   |
| `InvalidValueFormatException`     | an operation gets a value of the wrong shape | none                   |
| `MaxDepthExceededException`       | the expression nests too deep                | none                   |
| `InvalidEvaluation`               | an operation can't evaluate its input        | none                   |
| `AuthenticationRequiredException` | an action needs auth and there's no identity | the action name        |
| `InvalidTokenException`           | a bearer token can't be verified             | none                   |

## Debug mode

Debug mode is controlled by the `runner.debug` config value. It's `false` by default,
and you should keep it that way in production:

```php
WebApp::createDefault([
    'config' => [
        'global' => [
            'runner' => ['debug' => getenv('APP_ENV') === 'local'],
        ],
    ],
])->run();
```

The difference is what the client is allowed to see.

With debug off (production), internal errors are opaque and nothing leaks. A failed
validation is a client error, so it still returns its message and details so the
caller can fix the request:

```json
{ "error": "Request validation failed.", "details": ["get", { "0": "missing key" }] }
```

But an internal fault doesn't. It collapses to a generic message:

```json
{ "error": "An unexpected error occurred." }
```

With debug on (development), every error shows its real message and gains a `debug`
block with the exception type, the file and line it came from, and the stack trace.
So the same internal fault becomes:

```json
{
    "error": "SQLSTATE[HY000]: connection refused",
    "debug": {
        "type": "PDOException",
        "location": "/app/src/Actions/GetUserAction.php:21",
        "trace": ["#0 /app/src/...", "#1 /app/src/..."]
    }
}
```

The `debug` block exposes paths and traces, so never enable it on a public deployment.

## Making your own errors client-safe

An exception your action throws is treated as internal by default, so its message is
hidden in production. When you want the client to see it (a "not found", a bad input),
implement `ClientExceptionInterface`. Its one method returns extra client-safe details,
or `null`:

```php
use ArekX\RestFn\Contracts\ClientExceptionInterface;

class UserNotFoundException extends \RuntimeException implements ClientExceptionInterface
{
    public function getClientDetails(): ?array
    {
        return null;
    }
}
```

Now the message comes through to the client even in production, and you can return
structured `details` if there's something useful to add.

## Replacing the error handling

The error handling is just a middleware, so you can swap it. Setting
`runner.middleware` replaces the default stack, so list your own handler (and keep the
[authentication middleware](authentication.md) if you use it):

```php
'global' => ['runner' => ['middleware' => [
    App\Middleware\MyErrorMiddleware::class,
    AuthenticationMiddleware::class,
]]],
```

Your middleware does the same job: catch around `$next` and return a response. See
[Middleware](middleware.md) for how that fits together.
```
