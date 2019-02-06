<?php
/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

namespace ArekX\JsonQL\Rest\Handlers;


class Definer implements HandlerInterface
{
    /**
     * Returns handler request type.
     *
     * @return string
     */
    public static function requestType(): string
    {
        return 'define';
    }

    /**
     * Returns handler response type.
     *
     * @return string
     */
    public function responseType(): string
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