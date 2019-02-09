<?php
/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

namespace tests\Mock;

use ArekX\JsonQL\Config\Config;
use ArekX\JsonQL\Helpers\DI;
use ArekX\JsonQL\Rest\Application;
use ArekX\JsonQL\Rest\Handlers\Reader;
use ArekX\JsonQL\Rest\Services\JsonResponse;
use ArekX\JsonQL\Interfaces\RequestInterface;
use ArekX\JsonQL\Interfaces\ResponseInterface;

class MockRestConfig extends Config
{
    protected function getCoreConfig(): array
    {
        return [
            RequestInterface::class => MockRequest::class,
            ResponseInterface::class => MockResponse::class,
            JsonResponse::class => MockJsonResponse::class,
            Reader::class => [
                'namespace' => ''
            ]
        ];
    }

    protected function getAppConfig()
    {
        return [
            'handlers' => [
                MockHandler::class
            ]
        ];
    }

    /**
     * Returns application class used to bootstrap the application and configure it.
     * @return string
     */
    protected function getApplicationClass(): string
    {
        return Application::class;
    }
}