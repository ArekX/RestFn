<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Rest\Handlers;


use ArekX\JsonQL\Helpers\Value;
use ArekX\JsonQL\Rest\Config;
use ArekX\JsonQL\Services\ReaderInterface;

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
        $results = [];
        $appClass = $this->namespace;
        foreach ($data as $readerName => $item) {
            // TODO: Name validation.
            $className = preg_replace_callback('/(^(.)|[-_](.))/', function($matches) {
                return strtr(strtoupper($matches[1]), ['_' => '', '-' => '']);
            }, $readerName);

            $readerClass = "{$appClass}\\{$className}";

            try {
                /** @var ReaderInterface $instance */
                $instance = $this->config->getDI()->get($readerClass);
                $results[$readerName] = $instance->run();
            } catch (\DI\NotFoundException $e) {
                $results[$readerName] = 'Reader does not exist.';
            } catch (\Exception $e) {
                $results[$readerName] = $e;
            }

        }

        return $results;
    }
}