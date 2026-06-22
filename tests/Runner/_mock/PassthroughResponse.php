<?php


namespace tests\Runner\_mock;


use ArekX\RestFn\Runner\Contracts\ResponseInterface;

class PassthroughResponse implements ResponseInterface
{
    public function respond(mixed $result): mixed
    {
        return $result;
    }
}
