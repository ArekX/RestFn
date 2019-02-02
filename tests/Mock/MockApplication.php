<?php
/**
  * @author Aleksandar Panic
  * @link https://jsonql.readthedocs.io/
  * @license: http://www.apache.org/licenses/LICENSE-2.0
  * @since 1.0.0
 **/

namespace tests\Mock;

use ArekX\JsonQL\MainApplication;

class MockApplication extends MainApplication
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