<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Rest\Services;

use ArekX\JsonQL\Services\Request\RequestInterface;

class Request implements RequestInterface
{
    /** @var null|array */
    protected $body = null;

    /**
     * @codeCoverageIgnore
     */
    public function getBody(): array
    {
        if ($this->body !== null) {
            return $this->body;
        }

        $this->body = @json_decode(file_get_contents('php://input'), true);

        if (empty($this->body)) {
            $this->body = [];
        }

        return $this->body;
    }
}