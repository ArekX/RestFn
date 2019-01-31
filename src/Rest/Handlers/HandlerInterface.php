<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Rest\Handlers;


interface HandlerInterface
{
    /**
     * Returns handler request type.
     *
     * @return string
     */
    public static function requestType(): string;

    /**
     * Returns handler response type.
     *
     * @return string
     */
    public function responseType(): string;

    /**
     * Handle request.
     *
     * @param $data array Request data
     * @return array
     */
    public function handle(array $data): array;
}