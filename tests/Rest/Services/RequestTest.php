<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace tests\Rest\Services;

use ArekX\JsonQL\Rest\Services\Request;
use ArekX\JsonQL\Services\Request\RequestInterface;
use tests\TestCase;

class RequestTest extends TestCase
{
    public function testGetEmptyBody()
    {
        $mockRequest = $this->createMock(Request::class);
        $mockRequest->expects($this->once())->method('getBody')->willReturn([]);
        $this->di->set(RequestInterface::class, $mockRequest);

        $body = $this->di->get(RequestInterface::class)->getBody();
        $this->assertEmpty($body);
    }
}