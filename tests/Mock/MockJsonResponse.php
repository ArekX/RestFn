<?php
/**
  * @author Aleksandar Panic
  * @link https://jsonql.readthedocs.io/
  * @license: http://www.apache.org/licenses/LICENSE-2.0
  * @since 1.0.0
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