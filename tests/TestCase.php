<?php
/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

namespace tests;

use ArekX\JsonQL\Config\Config;
use ArekX\JsonQL\Helpers\DI;
use ArekX\JsonQL\MainApplication;
use Auryn\Injector;
use DI\Container;
use tests\Mock\MockConfig;

class TestCase extends \PHPUnit\Framework\TestCase
{
    /** @var Config */
    protected $config;

    /** @var MainApplication */
    protected $app;

    /** @var Injector */
    protected $di;

    public function setUp()
    {
        $this->config = $this->createConfig();
        $this->di = $this->config->getDI();

        DI::bootstrap($this->config);

        $this->app = $this->di->make(MainApplication::class);
    }

    protected function createConfig()
    {
        return new MockConfig();
    }
}