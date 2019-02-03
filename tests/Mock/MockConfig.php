<?php
/**
  * @author Aleksandar Panic
  * @link https://jsonql.readthedocs.io/
  * @license: http://www.apache.org/licenses/LICENSE-2.0
  * @since 1.0.0
 **/

namespace tests\Mock;

use ArekX\JsonQL\Config\Config;
use ArekX\JsonQL\Helpers\DI;
use ArekX\JsonQL\Rest\Handlers\Reader;
use ArekX\JsonQL\Interfaces\RequestInterface;
use ArekX\JsonQL\Interfaces\ResponseInterface;

class MockConfig extends Config
{
    /**
     * @inheritdoc
     */
    protected function getCoreConfig(): array
    {
        return [
            RequestInterface::class => DI::wireClass(MockRequest::class),
            ResponseInterface::class => DI::wireClass(MockResponse::class),
            Reader::class => DI::wireSetup(Reader::class, [
                'namespace' => ''
            ])
        ];
    }

    /**
     * @inheritdoc
     */
    protected function getApplicationClass(): string
    {
        return MockApplication::class;
    }
}