<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Rest\Handlers;


use ArekX\JsonQL\Helpers\DI;
use ArekX\JsonQL\Helpers\Value;
use ArekX\JsonQL\Rest\Config;
use ArekX\JsonQL\Interfaces\ReaderInterface;
use ArekX\JsonQL\Validation\InvalidTypeException;

class Reader implements HandlerInterface
{
    public $namespace;
    protected $config;

    public function __construct(Config $config, array $setup)
    {
        $this->config = $config;

        Value::setup($this, $setup, [
            'namespace' => ''
        ]);
    }

    /**
     * Returns handler request type.
     *
     * @return string
     */
    public static function getRequestType(): string
    {
        return 'read';
    }

    /**
     * Returns handler response type.
     *
     * @return string
     */
    public function getResponseType(): string
    {
        return 'read';
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