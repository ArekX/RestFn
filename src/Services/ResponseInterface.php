<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Services;


use ArekX\JsonQL\Rest\Handlers\HandlerInterface;

interface ResponseInterface
{
    /**
     * Sets response data array.
     * @param array $data
     */
    public function write(HandlerInterface $handler, array $data): void;

    /**
     * Outputs response.
     */
    public function output(): void;
}