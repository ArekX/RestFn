<?php


namespace tests\Runner\_mock;


use ArekX\RestFn\Parser\Context;
use ArekX\RestFn\Runner\Contracts\MiddlewareInterface;
use ArekX\RestFn\Runner\Request;

class ShortCircuitMiddleware implements MiddlewareInterface
{
    public function process(Request $request, Context $context, callable $next): mixed
    {
        // Does not call $next: the request is short-circuited.
        return 'short-circuited';
    }
}
