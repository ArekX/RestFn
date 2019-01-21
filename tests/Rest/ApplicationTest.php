<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace tests\Rest;

use ArekX\JsonQL\BaseApplication;
use ArekX\JsonQL\Rest\Application;
use ArekX\JsonQL\Rest\Handlers\InvalidHandlerException;
use ArekX\JsonQL\Services\Request\RequestInterface;
use tests\Mock\MockHandler;
use tests\Mock\MockRequest;

class ApplicationTest extends TestCase
{
    public function testInitializedWithHandlers()
    {
        /** @var Application $app */
        $app = $this->di->get(BaseApplication::class);
        $this->assertEquals($app->handlers, [MockHandler::class]);
    }

    public function testHandlersAreNotRunWhenNotInRequest()
    {
        /** @var MockRequest $request */
        $request = $this->di->get(RequestInterface::class);
        $request->body = [];

        $this->app->run();

        $handler = $this->di->get(MockHandler::class);

        $this->assertFalse($handler->isRun);
    }

    public function testHandlersAreRunFromRequest()
    {
        /** @var MockRequest $request */
        $request = $this->di->get(RequestInterface::class);
        $request->body = [MockHandler::getRequestType() => ['data' => 'value']];

        $this->app->run();

        $handler = $this->di->get(MockHandler::class);

        $this->assertTrue($handler->isRun);
        $this->assertEquals($handler->data, ['data' => 'value']);
    }

    public function testExceptionIsThrownOnUnknownRequestType()
    {
        /** @var MockRequest $request */
        $request = $this->di->get(RequestInterface::class);
        $request->body = ['unknown' => ['data' => 'value']];

        $this->expectException(InvalidHandlerException::class);
        $this->app->run();
    }
}