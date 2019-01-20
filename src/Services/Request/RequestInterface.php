<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Services\Request;


interface RequestInterface
{
    /**
     * Returns request body.
     *
     * @return array
     */
    public function getBody(): array;
}