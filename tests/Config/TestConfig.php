<?php
/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

namespace tests\Config;


use ArekX\JsonQL\Config\Config;
use tests\Mock\MockApplication;

class TestConfig extends Config
{
    /**
     * Returns initial main application config.
     * @return mixed
     */
    protected function getAppConfig()
    {
        return null;
    }

    /**
     * Returns application class used to bootstrap the application and configure it.
     * @return string
     */
    protected function getApplicationClass(): string
    {
        return MockApplication::class;
    }
}