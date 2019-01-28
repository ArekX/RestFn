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
use ArekX\JsonQL\Interfaces\RequestInterface;
use ArekX\JsonQL\Interfaces\ResponseInterface;

class MockConfig extends Config
{
    protected function getCoreServices()
    {
        return [
            MainApplication::class => DI::wireSetup(MockApplication::class, []),
            RequestInterface::class => DI::wireClass(MockRequest::class),
            ResponseInterface::class => DI::wireClass(MockResponse::class),
            Reader::class => DI::wireSetup(Reader::class, [
                'namespace' => ''
            ])
        ];
    }
}