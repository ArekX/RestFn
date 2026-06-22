# Middleware

Middleware wrap the handling of a request. They run on the way in, before the request
is validated and evaluated, and on the way out, after the result is produced. You use
them for things that apply to the whole request, like authentication, logging, or
wrapping the result in an envelope.

## How it works

Middleware nest like an onion. Each middleware gets the next handler and decides when
to call it. Code you write before calling `$next` runs on the way in. Code you write
after runs on the way out.

A middleware implements `MiddlewareInterface`:

```php
use ArekX\RestFn\Parser\Context;
use ArekX\RestFn\Runner\Contracts\MiddlewareInterface;
use ArekX\RestFn\Runner\Request;

class TimingMiddleware implements MiddlewareInterface
{
    public function process(Request $request, Context $context, callable $next): mixed
    {
        $start = microtime(true);     // on the way in

        $result = $next($request, $context);

        error_log('took ' . (microtime(true) - $start)); // on the way out

        return $result;
    }
}
```

`$next` is the rest of the chain, ending in the core handler that validates and
evaluates the request. It returns the result, which you can return as is or change.

## Registering middleware

Middleware are configured on the `Runner` as a list of class names. They're resolved
through the container, so a middleware can declare its own dependencies in its
constructor and they'll be injected.

`WebApp::createDefault()` wires a default stack for you: `ErrorMiddleware` (outermost)
and `AuthenticationMiddleware`. Setting the `runner.middleware` config value replaces
that stack entirely, so include the defaults yourself if you still want them:

```php
'global' => ['runner' => ['middleware' => [
    ErrorMiddleware::class,          // keep error handling outermost
    AuthenticationMiddleware::class,
    TimingMiddleware::class,
]]],
```

## Order

The first middleware in the list is the outermost. It runs first on the way in and
last on the way out. With two middleware `[Outer, Inner]` the order is:

```
Outer (before)
  Inner (before)
    validate + evaluate
  Inner (after)
Outer (after)
```

So if you want something to wrap the final result, put it first in the list.

## Changing the result

Because a middleware returns whatever it wants, it can transform the result. This
wraps every result in an envelope:

```php
class EnvelopeMiddleware implements MiddlewareInterface
{
    public function process(Request $request, Context $context, callable $next): mixed
    {
        return ['ok' => true, 'data' => $next($request, $context)];
    }
}
```

## Short-circuiting

A middleware doesn't have to call `$next`. If it returns without calling it, the
request is short-circuited and the rest of the chain (including validation and
evaluation) never runs. This is how you reject a request early:

```php
public function process(Request $request, Context $context, callable $next): mixed
{
    if (!$this->allowed($request)) {
        return ['error' => 'forbidden'];
    }

    return $next($request, $context);
}
```

## A note on errors

The on-the-way-out part of a middleware only runs if `$next` returns normally. If
validation fails or an action throws, the exception travels up past your after-code.
If a middleware needs to run no matter what (for logging, say), wrap `$next` in a
`try`/`finally`. If it needs to turn an error into a result, catch the exception
around `$next`.

That's what the built-in `ErrorMiddleware` does, which is why it sits outermost by
default: it catches anything thrown below it and turns it into a clean response. See
[Error handling](error-handling.md).

## Error handling

The outermost middleware in the default stack is `ErrorMiddleware`. It wraps the whole
pipeline in a `try`/`catch` and turns anything thrown below it into a JSON error,
hiding internal details unless debug mode is on. It has its own page:
[Error handling](error-handling.md).

## Where middleware live

The built-in middleware are in `ArekX\RestFn\Runner\Middleware`: the [authentication
middleware](authentication.md) and the error middleware. Put your own middleware
wherever you like in your project. They only need to implement `MiddlewareInterface`.
