<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace tests\Mock;


use ArekX\JsonQL\Rest\Handlers\HandlerInterface;

class MockHandler implements HandlerInterface
{
    public $data;

    public $result = [];

    public $isRun = false;

    /**
     * Returns handler request type.
     *
     * @return string
     */
    public static function requestType(): string
    {
        return 'test';
    }

    /**
     * Returns handler response type.
     *
     * @return string
     */
    public function responseType(): string
    {
        return 'test';
    }

    /**
     * Handle request.
     *
     * @param $data array Request data
     * @return array
     */
    public function handle(array $data): array
    {
        $this->data = $data;
        $this->isRun = true;
        return $this->result;
    }
}