<?php
/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
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