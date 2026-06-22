<?php


namespace tests\Runner\_mock;


use ArekX\RestFn\Runner\Contracts\RequestParserInterface;
use ArekX\RestFn\Runner\Request;

class StubRequestParser implements RequestParserInterface
{
    public function __construct(
        private Request $request,
    ) {}

    public function parse(): Request
    {
        return $this->request;
    }
}
