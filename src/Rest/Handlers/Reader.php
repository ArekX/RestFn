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
use ArekX\JsonQL\Rest\Interfaces\ReaderInterface;
use ArekX\JsonQL\Values\InvalidValueException;
use ArekX\JsonQL\Values\ReaderRequestValue;

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
        try {
            return $this->tryHandleReaders($data);
        } catch (InvalidValueException $e) {
            return $e->validationErrors;
        }
    }

    /**
     * Runs reader class and passes data to it.
     *
     * @param $readerClass Reader class which will be run.
     * @param array $data Data which will be passed.
     * @return array
     * @throws InvalidValueException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function runReader($readerClass, array $data): array
    {
        /** @var ReaderInterface $instance */
        $instance = $this->config->make($readerClass, ['data' => $data]);
        return $instance->run($data);
    }

    protected function isMultiRequest(array $request)
    {
        $keys = array_keys($request);
        return count($keys) === count(array_filter($keys, 'is_int'));
    }

    protected function tryHandleReaders(array $data): array
    {
        $responses = [];

        foreach (ReaderRequestValue::from($data)->iterate() as $key => $request) {
            $readerClass = "{$this->namespace}\\" . ucfirst($key);

            if (!class_exists($readerClass)) {
                $responses[$key] = [
                    'type' => 'not_found',
                    'reader' => $key,
                    'resolvedClass' => $readerClass
                ];
                continue;
            }

            if ($this->isMultiRequest($request)) {
                // Check if there is multi request handler which can optimize the data fetching.
                $responses[$key] = [];
                foreach ($request as $index => $singleRequest) {
                    $request[$key][$index] = $this->runReader($readerClass, $singleRequest);
                }

                continue;
            }

            $responses[$key] = $this->runReader($readerClass, $request);
        }

        return $responses;
    }
}