<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Rest\Services;

use ArekX\JsonQL\Rest\Handlers\HandlerInterface;
use ArekX\JsonQL\Interfaces\ResponseInterface;

class JsonResponse implements ResponseInterface
{
    protected $data = [];

    /**
     * @codeCoverageIgnore
     */
    protected function setupHeaders()
    {
        header('Content-Type: application/json');
    }

    /**
     * Clears all data from result.
     */
    public function clearAll()
    {
        $this->data = [];
    }

    /**
     * Clears handler result from response.
     *
     * @param HandlerInterface $handler
     */
    public function clearHandler(HandlerInterface $handler)
    {
        $this->data[$handler->responseType()] = (object)[];
    }

    /**
     * Outputs response.
     */
    public function output(): void
    {
        $this->setupHeaders();

        if (empty($this->data)) {
            echo '{}';
            return;
        }

        echo json_encode($this->data);
    }

    /**
     * Sets response data array.
     * @param array $data
     */
    public function write(HandlerInterface $handler, array $data): void
    {
        $this->data[$handler->responseType()] = !empty($data) ? $data : (object)[];
    }
}