<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Rest\Services;

use ArekX\JsonQL\Rest\Handlers\HandlerInterface;
use ArekX\JsonQL\Services\ResponseInterface;

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
        $this->data[$handler->getResponseType()] = $data;
    }
}