<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Interfaces;


interface RequestInterface
{
    /**
     * Returns request body.
     *
     * @return array
     */
    public function read(): array;
}