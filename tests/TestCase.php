<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace tests;

use ArekX\JsonQL\BaseApplication;
use ArekX\JsonQL\Config\ConfigInterface;
use DI\Container;
use tests\Mock\MockConfig;

class TestCase extends \PHPUnit\Framework\TestCase
{
    /** @var ConfigInterface */
    protected $config;

    /** @var BaseApplication */
    protected $app;

    /** @var Container */
    protected $di;

    public function setUp()
    {
        $this->config = $this->createConfig();
        $this->di = $this->config->getDI();
        $this->app = $this->di->get(BaseApplication::class);
    }

    protected function createConfig()
    {
        return new MockConfig();
    }
}