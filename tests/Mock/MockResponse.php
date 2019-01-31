<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace tests\Mock;

use ArekX\JsonQL\Rest\Handlers\HandlerInterface;
use ArekX\JsonQL\Interfaces\ResponseInterface;

class MockResponse implements ResponseInterface
{
    public $data = [];
    public $outputCalled = false;

    public function clear()
    {
        $this->data = [];
        $this->outputCalled = false;
    }

    /**
     * Sets response data array.
     * @param array $data
     */
    public function set(array $data): void
    {
        $this->data = $data;
    }

    /**
     * Outputs response.
     */
    public function output(): void
    {
        $this->outputCalled = true;
    }

    /**
     * Sets response data array.
     * @param array $data
     */
    public function write(HandlerInterface $handler, array $data): void
    {
        $this->data[$handler->responseType()] = $data;
    }
}