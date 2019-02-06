<?php
/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

namespace ArekX\JsonQL\Interfaces;


use ArekX\JsonQL\Rest\Handlers\HandlerInterface;

interface ResponseInterface
{
    /**
     * Sets response data array.
     * @param HandlerInterface $handler
     * @param array $data
     */
    public function write(HandlerInterface $handler, array $data): void;

    /**
     * Outputs response.
     */
    public function output(): void;
}