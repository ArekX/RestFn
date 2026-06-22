# Runner

The `Runner` handles one request from start to finish. It reads the request, runs
your middleware, validates and evaluates the operation tree, and produces the
response.

You usually do not create the `Runner` yourself. The `WebApp` resolves it for you
and calls `run()`. But it helps to know what it does.

## What run() does

When you call `run()` the Runner:

1. Creates a fresh `Context` for this request.
2. Asks the request parser for the request. A parse failure is deferred so the
   error middleware can render it, rather than escaping the pipeline.
3. Wraps the core handling in the configured middleware.
4. In the core: validates the request body, then evaluates it.
5. Passes the result to the response and returns it.

In code the core is simple:

```php
$errors = $this->parser->validate($request->body, $context);

if ($errors !== null) {
    throw new InvalidRequestException('Request validation failed.', $errors);
}

return $this->parser->evaluate($request->body, $context);
```

So validation always happens before evaluation. If the request is malformed,
nothing runs and an `InvalidRequestException` is thrown carrying the validation
errors.

## Reading the request

The Runner reads the request through a `RequestParserInterface`. The default is
`JsonRequestParser`, which reads the raw body from `php://input` and decodes it as
JSON. The decoded body is the operation tree, and the headers are kept so
middleware (like authentication) can read them.

You can point the parser at a different stream with the `runner.inputStream`
config value, which is useful in tests:

```php
'config' => ['global' => ['runner' => ['inputStream' => 'php://input']]],
```

To read the request differently (form data, a different format), bind your own
`RequestParserInterface`.

## Producing the response

The result of evaluation is passed to a `ResponseInterface`. The default
`JsonResponse` encodes the result as JSON and sets the `application/json` content
type. `run()` returns the encoded body, which the `WebApp` (or your `index.php`)
outputs.

To return something other than JSON, bind your own `ResponseInterface`.

## Middleware

The Runner wraps the core handling in the middleware you configure. Middleware run
on the way in and on the way out, so they can do things like authentication before
the request is handled and shape the result after.

`WebApp::createDefault()` wires a default stack — `ErrorMiddleware` (outermost) and
`AuthenticationMiddleware`. Setting `runner.middleware` replaces that stack, so
include the defaults yourself if you still want them:

```php
'global' => ['runner' => ['middleware' => [
    ErrorMiddleware::class,
    AuthenticationMiddleware::class,
    // ...your middleware
]]],
```

Middleware are explained in detail in [Middleware](middleware.md).

## Errors

Several things can stop a request inside the Runner:

- **A malformed body** — the request parser cannot decode the body, raising an
  `InvalidRequestException`.
- **Invalid request body** — validation fails, and an `InvalidRequestException`
  is thrown with the errors available on `$exception->errors`.
- **An action requires authentication** — an `AuthenticationRequiredException` is
  thrown by the operation that tried to run the action.

With the default setup you do not catch these yourself: the `ErrorMiddleware` at
the top of the stack catches anything thrown below it — including a deferred parse
failure — and turns it into a JSON error response, hiding internal details unless
`runner.debug` is on. See [Error handling](middleware.md#error-handling).

So your `index.php` stays a single line:

```php
echo WebApp::createDefault($config)->run();
```
