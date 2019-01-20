<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Rest;


use ArekX\JsonQL\BaseApplication;
use ArekX\JsonQL\Rest\Handlers\Performer;
use ArekX\JsonQL\Rest\Handlers\Reader;
use ArekX\JsonQL\Rest\Handlers\Writer;
use ArekX\JsonQL\Rest\Services\Request;
use ArekX\JsonQL\Services\Request\RequestInterface;
use function DI\autowire;

class Config extends \ArekX\JsonQL\Config\Config
{
    protected function getCoreServices()
    {
        return [
            BaseApplication::class => autowire(Application::class)->constructorParameter('setup', [
                'handlers' => [
                    Performer::class,
                    Reader::class,
                    Writer::class
                ]
            ]),
            RequestInterface::class => autowire(Request::class)
        ];
    }
}