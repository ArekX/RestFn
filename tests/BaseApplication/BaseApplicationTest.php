<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace tests\BaseApplication;

use ArekX\JsonQL\BaseApplication;
use ArekX\JsonQL\Helpers\DI;
use ArekX\JsonQL\Rest\Application;
use ArekX\JsonQL\Rest\Handlers\Performer;
use function DI\autowire;
use tests\TestCase;

class BaseApplicationTest extends TestCase
{
    public function testCreateSingletonApp()
    {
        $app = $this->di->get(BaseApplication::class);
        $this->assertEquals($this->app, $app);
    }
}