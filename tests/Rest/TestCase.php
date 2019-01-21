<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
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