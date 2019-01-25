<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

use ArekX\JsonQL\Config\Config;
use ArekX\JsonQL\Helpers\DI;
use ArekX\JsonQL\Rest\Handlers\Reader;

return [
    Config::SERVICES => [
        Reader::class => DI::wireSetup(Reader::class, [
            'namespace' => '\App\Readers'
        ])
    ]
];