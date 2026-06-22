<?php


namespace tests\Runner\_mock;


use ArekX\RestFn\Parser\Context;
use ArekX\RestFn\Runner\Contracts\MiddlewareInterface;
use ArekX\RestFn\Runner\Request;

class OuterMiddleware implements MiddlewareInterface
{
    public function process(Request $request, Context $context, callable $next): mixed
    {
        Recorder::$log[] = 'outer:before';
        $result = $next($request, $context);
        Recorder::$log[] = 'outer:after';

        return $result;
    }
}
