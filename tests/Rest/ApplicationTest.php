<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace tests\Rest;

use ArekX\JsonQL\BaseApplication;
use ArekX\JsonQL\Rest\Application;
use tests\Mock\MockHandler;

class ApplicationTest extends TestCase
{
    public function testInitializedWithEmptyHandlers()
    {
        /** @var Application $app */
        $app = $this->di->get(BaseApplication::class);
        $this->assertEquals($app->handlers, [MockHandler::class]);
    }
}