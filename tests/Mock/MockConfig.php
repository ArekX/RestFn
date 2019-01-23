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
use ArekX\JsonQL\Rest\Handlers\Reader;
use ArekX\JsonQL\Services\RequestInterface;
use ArekX\JsonQL\Services\ResponseInterface;

class MockConfig extends Config
{
    protected function getCoreServices()
    {
        return [
            MainApplication::class => DI::setup(MockApplication::class, []),
            RequestInterface::class => DI::class(MockRequest::class),
            ResponseInterface::class => DI::class(MockResponse::class),
            Reader::class => DI::setup(Reader::class, [
                'namespace' => ''
            ])
        ];
    }
}