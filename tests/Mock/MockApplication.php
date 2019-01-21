<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace tests\Mock;

use ArekX\JsonQL\BaseApplication;

class MockApplication extends BaseApplication
{
    public $setupValues = [];
    public $ran = false;

    public function setup($values): void
    {
        $this->setupValues = $values;
    }

    public function run(): void
    {
        $this->ran = true;
    }
}