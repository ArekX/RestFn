<?php
/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

namespace tests\Rest;

use tests\Mock\MockRestConfig;

class TestCase extends \tests\TestCase
{
    protected function createConfig()
    {
        return new MockRestConfig();
    }
}