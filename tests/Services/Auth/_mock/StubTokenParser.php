<?php


namespace tests\Services\Auth\_mock;


use ArekX\RestFn\Services\Auth\Contracts\TokenParserInterface;

class StubTokenParser implements TokenParserInterface
{
    public function __construct(
        private mixed $payload,
    ) {}

    public function parse(string $token): mixed
    {
        return $this->payload;
    }
}
