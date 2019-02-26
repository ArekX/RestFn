<?php
/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

namespace ArekX\JsonQL\Rest\Services;

use ArekX\JsonQL\Rest\Handlers\HandlerInterface;
use ArekX\JsonQL\Interfaces\ResponseInterface;

/**
 * Class JsonResponse
 *
 * Class for handling data for JSON responses.
 *
 * @package ArekX\JsonQL\Rest\Services
 */
class JsonResponse implements ResponseInterface
{
    /**
     * Data to be written to the response.
     *
     * @var array
     */
    protected $data = [];

    /**
     * @codeCoverageIgnore
     */
    protected function setupHeaders()
    {
        header('Content-Type: application/json');
    }

    /**
     * Clears all data from result.
     */
    public function clearAll()
    {
        $this->data = [];
    }

    /**
     * Clears handler result from response.
     *
     * @param HandlerInterface $handler
     */
    public function clearHandler(HandlerInterface $handler)
    {
        $this->data[$handler->responseType()] = (object)[];
    }

    /**
     * Outputs response.
     */
    public function output(): void
    {
        $this->setupHeaders();

        if (empty($this->data)) {
            echo '{}';
            return;
        }

        $this->data['meta']['memory'] = round(memory_get_usage(true), 4);
        $this->data['meta']['memoryKB'] = round(memory_get_usage(true) / 1024, 4) . ' KB';
        $this->data['meta']['memoryMB'] = round(memory_get_usage(true) / 1024 / 1024, 4) . ' MB';

        echo json_encode($this->data);
    }

    /**
     * Sets response data array.
     * @param HandlerInterface $handler
     * @param array $data
     */
    public function writeHandler(HandlerInterface $handler, array $data): void
    {
        $this->data[$handler->responseType()] = !empty($data) ? $data : (object)[];
    }

    /**
     * Sets response data array.
     * @param array $data
     */
    public function write(array $data): void
    {
        $this->data = $data;
    }

    /**
     * Sets metadata specified by key.
     *
     * @param string $key Key which will be used to retrieve the value.
     */
    public function writeMeta(string $key, $value): void
    {
        $this->data['meta'][$key] = $value;
    }
}