<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace tests\Mock;

use ArekX\JsonQL\MainApplication;
use ArekX\JsonQL\Helpers\DI;
use ArekX\JsonQL\Rest\Application;
use ArekX\JsonQL\Rest\Config;
use ArekX\JsonQL\Services\Request\RequestInterface;

class MockRestConfig extends Config
{
    protected function getCoreServices()
    {
        return [
            MainApplication::class => DI::setup(Application::class, [
                'handlers' => [
                    MockHandler::class
                ]
            ]),
            RequestInterface::class => DI::class(MockRequest::class)
        ];
    }
}