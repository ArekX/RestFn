<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace tests;

use ArekX\JsonQL\MainApplication;
use ArekX\JsonQL\Config\ConfigInterface;
use DI\Container;
use tests\Mock\MockConfig;

class TestCase extends \PHPUnit\Framework\TestCase
{
    /** @var ConfigInterface */
    protected $config;

    /** @var MainApplication */
    protected $app;

    /** @var Container */
    protected $di;

    public function setUp()
    {
        $this->config = $this->createConfig();
        $this->di = $this->config->getDI();
        $this->app = $this->di->get(MainApplication::class);
    }

    protected function createConfig()
    {
        return new MockConfig();
    }
}