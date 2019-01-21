<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace tests\BaseApplication;

use ArekX\JsonQL\MainApplication as BaseApplication;
use tests\TestCase;

class BaseApplicationTest extends TestCase
{
    public function testCreateSingletonApp()
    {
        $app = $this->di->get(BaseApplication::class);
        $this->assertEquals($this->app, $app);
    }
}