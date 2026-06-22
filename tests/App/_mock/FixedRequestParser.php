<?php


namespace tests\App\_mock;


use ArekX\RestFn\Runner\Contracts\RequestParserInterface;
use ArekX\RestFn\Runner\Request;

class FixedRequestParser implements RequestParserInterface
{
    public function parse(): Request
    {
        return new Request(['return', 99]);
    }
}
