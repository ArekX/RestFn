<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Rest;


use ArekX\JsonQL\Helpers\DI;
use ArekX\JsonQL\MainApplication;
use ArekX\JsonQL\Rest\Handlers\Performer;
use ArekX\JsonQL\Rest\Handlers\Reader;
use ArekX\JsonQL\Rest\Handlers\Writer;
use ArekX\JsonQL\Rest\Services\JsonResponse;
use ArekX\JsonQL\Rest\Services\Request;
use ArekX\JsonQL\Services\RequestInterface;
use ArekX\JsonQL\Services\ResponseInterface;

class Config extends \ArekX\JsonQL\Config\Config
{
    protected function getCoreServices()
    {
        return [
            MainApplication::class => DI::wireSetup(Application::class, [
                'handlers' => [
                    Performer::class,
                    Reader::class,
                    Writer::class
                ]
            ]),
            RequestInterface::class => DI::wireClass(Request::class),
            ResponseInterface::class => DI::wireClass(JsonResponse::class),
        ];
    }
}