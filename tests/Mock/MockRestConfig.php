<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace tests\Mock;

use ArekX\JsonQL\Config\Config;
use ArekX\JsonQL\MainApplication;
use ArekX\JsonQL\Helpers\DI;
use ArekX\JsonQL\Rest\Application;
use ArekX\JsonQL\Rest\Handlers\Reader;
use ArekX\JsonQL\Rest\Services\JsonResponse;
use ArekX\JsonQL\Services\RequestInterface;
use ArekX\JsonQL\Services\ResponseInterface;

class MockRestConfig extends Config
{
    protected function getCoreServices()
    {
        return [
            MainApplication::class => DI::wireSetup(Application::class, [
                'handlers' => [
                    MockHandler::class
                ]
            ]),
            RequestInterface::class => DI::wireClass(MockRequest::class),
            ResponseInterface::class => DI::wireClass(MockResponse::class),
            JsonResponse::class => DI::wireClass(MockJsonResponse::class),
            Reader::class => DI::wireSetup(Reader::class, [
                'namespace' => ''
            ])
        ];
    }
}