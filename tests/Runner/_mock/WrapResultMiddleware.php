<?php


namespace tests\Runner\_mock;


use ArekX\RestFn\Parser\Context;
use ArekX\RestFn\Runner\Contracts\MiddlewareInterface;
use ArekX\RestFn\Runner\Request;

class WrapResultMiddleware implements MiddlewareInterface
{
    public function process(Request $request, Context $context, callable $next): mixed
    {
        return ['result' => $next($request, $context)];
    }
}
