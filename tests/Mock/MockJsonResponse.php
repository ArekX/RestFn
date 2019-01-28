<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace tests\Mock;

use ArekX\JsonQL\Rest\Handlers\HandlerInterface;
use ArekX\JsonQL\Rest\Services\JsonResponse;
use ArekX\JsonQL\Interfaces\ResponseInterface;

class MockJsonResponse extends JsonResponse
{
    protected function setupHeaders()
    {
    }
}