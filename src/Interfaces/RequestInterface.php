<?php
/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

namespace ArekX\JsonQL\Interfaces;

/**
 * Interface RequestInterface
 *
 * Interface used for requests.
 *
 * @package ArekX\JsonQL\Interfaces
 */
interface RequestInterface
{
    /**
     * Returns request body.
     *
     * @return array
     */
    public function read(): array;
}