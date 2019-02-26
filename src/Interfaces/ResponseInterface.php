<?php
/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

namespace ArekX\JsonQL\Interfaces;


use ArekX\JsonQL\Rest\Handlers\HandlerInterface;

/**
 * Interface ResponseInterface
 *
 * Interface used for response.
 *
 * @package ArekX\JsonQL\Interfaces
 */
interface ResponseInterface
{
    /**
     * Sets response data array.
     * @param HandlerInterface $handler
     * @param array $data
     */
    public function writeHandler(HandlerInterface $handler, array $data): void;

    /**
     * Sets response data array.
     * @param array $data
     */
    public function write(array $data): void;

    /**
     * Sets metadata specified by key.
     *
     * @param string $key Key which will be used to retrieve the value.
     */
    public function writeMeta(string $key, $value): void;

    /**
     * Outputs response.
     */
    public function output(): void;
}