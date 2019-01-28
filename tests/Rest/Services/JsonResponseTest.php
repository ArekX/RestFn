<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace tests\Rest\Services;

use ArekX\JsonQL\Rest\Handlers\HandlerInterface;
use ArekX\JsonQL\Rest\Services\JsonResponse;
use ArekX\JsonQL\Interfaces\ResponseInterface;
use tests\Mock\MockHandler;
use tests\Rest\TestCase;

class JsonResponseTest extends TestCase
{
    public function testResponseIsOutput()
    {
        $handler = $this->getHandler();
        $result = $this->outputResponse($handler, ['key' => 'value']);
        $this->assertEquals('{"test":{"key":"value"}}', $result);
    }

    public function testAllDataIsCleared()
    {
        $handler = $this->getHandler();
        /** @var JsonResponse $response */
        $response = $this->di->get(JsonResponse::class);
        $response->write($handler, ['key' => 'value']);
        $response->clearAll();
        $this->assertEquals('{}', $this->getResponseString($response));
    }

    public function testSingleHandlerIsCleared()
    {
        $handler = $this->getHandler();
        /** @var JsonResponse $response */
        $response = $this->di->get(JsonResponse::class);
        $response->write($handler, ['key' => 'value']);
        $response->clearHandler($handler);
        $this->assertEquals('{"test":{}}', $this->getResponseString($response));
    }

    public function outputResponse(HandlerInterface $handler, array $data): string
    {
        /** @var JsonResponse $response */
        $response = $this->di->get(JsonResponse::class);
        $response->write($handler, $data);

        return $this->getResponseString($response);
    }

    public function getResponseString(ResponseInterface $response)
    {
        ob_start();
        $response->output();
        return ob_get_clean();
    }

    public function getHandler(): MockHandler
    {
        return $this->di->get(MockHandler::class);
    }
}