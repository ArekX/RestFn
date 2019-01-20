<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace tests\Rest\Services;

use ArekX\JsonQL\Services\Request\RequestInterface;
use tests\TestCase;

class RequestTest extends TestCase
{
    public function testGetEmptyBody()
    {
        $body = $this->di->get(RequestInterface::class)->getBody();
        $this->assertEmpty($body);
    }
}