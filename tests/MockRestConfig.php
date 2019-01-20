<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace tests;

use ArekX\JsonQL\BaseApplication;
use ArekX\JsonQL\Rest\Application;
use ArekX\JsonQL\Rest\Config;
use ArekX\JsonQL\Services\Request\RequestInterface;
use tests\Mock\MockRequest;

use function DI\autowire;

class MockRestConfig extends Config
{
    protected function getCoreServices()
    {
        return [
            BaseApplication::class => autowire(Application::class)->constructorParameter('setup', [
                'handlers' => []
            ]),
            RequestInterface::class => autowire(MockRequest::class)
        ];
    }
}