<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace tests;

use ArekX\JsonQL\BaseApplication;
use ArekX\JsonQL\Rest\Config;
use DI\Container;

class TestCase extends \PHPUnit\Framework\TestCase
{
    /** @var MockRestConfig */
    protected $config;

    /** @var BaseApplication */
    protected $app;

    /** @var Container */
    protected $di;

    public function setUp()
    {
        $this->config = new MockRestConfig();
        $this->di = $this->config->getDI();
        $this->app = $this->di->get(BaseApplication::class);
    }
}