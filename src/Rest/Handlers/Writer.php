<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Rest\Handlers;


class Writer implements HandlerInterface
{
    /**
     * Returns handler request type.
     *
     * @return string
     */
    public static function getRequestType(): string
    {
        return 'write';
    }

    /**
     * Returns handler response type.
     *
     * @return string
     */
    public function getResponseType(): string
    {
        return 'wrote';
    }

    /**
     * Handle request.
     *
     * @param $data array Request data
     * @return array
     */
    public function handle(array $data): array
    {
        return [];
    }
}