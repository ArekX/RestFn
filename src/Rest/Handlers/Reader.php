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
                $instance = DI::make($readerClass);
                $results[$readerName] = $instance->run();
//            } catch (\DI\NotFoundException $e) {
                // FIXME: Does not work well if some of the inner classes exist same error is output.
//                $results[$readerName] = 'Reader does not exist.';
            } catch (InvalidTypeException $e) {
                $results[$readerName] = [
                    'error' => get_class($e),
                    'validation' => $e->getErrors()
                ];
            } catch (\Exception $e) {
                $results[$readerName] = [
                    'message' => $e->getMessage(),
                    'code' => $e->getCode(),
                    'trace' => $e->getTrace()
                ];
            }

        }

        return $results;
    }
}