<?php
/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

namespace ArekX\JsonQL\Rest\Handlers;


use ArekX\JsonQL\Helpers\Value;
use ArekX\JsonQL\Rest\Config;

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
    public static function requestType(): string
    {
        return 'read';
    }

    /**
     * Returns handler response type.
     *
     * @return string
     */
    public function responseType(): string
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