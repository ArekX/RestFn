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

    /**
     * Returns request metadata specified by key.
     *
     * @param string $key Key which will be used to retrieve the value.
     * @param null|mixed $defaultValue Default value to be returned if key doesn't exist.
     * @return mixed Data specified by key or value from $defaultValue if not found.
     */
    public function getMeta(string $key, $defaultValue = null);
}