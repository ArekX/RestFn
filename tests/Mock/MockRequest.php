<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace tests\Mock;

class MockRequest implements \ArekX\JsonQL\Interfaces\RequestInterface
{
    public $body = [];

    /**
     * Returns request body.
     *
     * @return array
     */
    public function read(): array
    {
        return $this->body;
    }
}