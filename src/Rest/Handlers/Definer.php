<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Rest\Handlers;


class Definer implements HandlerInterface
{
    /**
     * Returns handler request type.
     *
     * @return string
     */
    public static function getRequestType(): string
    {
        return 'define';
    }

    /**
     * Returns handler response type.
     *
     * @return string
     */
    public function getResponseType(): string
    {
        return 'defined';
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