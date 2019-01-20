<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace tests\Mock;

class MockRequest implements \ArekX\JsonQL\Services\Request\RequestInterface
{
    public $body = [];

    /**
     * Returns request body.
     *
     * @return array
     */
    public function getBody(): array
    {
        return $this->body;
    }
}