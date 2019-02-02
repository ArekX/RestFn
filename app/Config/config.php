<?php
/**
  * @author Aleksandar Panic
  * @link https://jsonql.readthedocs.io/
  * @license: http://www.apache.org/licenses/LICENSE-2.0
  * @since 1.0.0
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