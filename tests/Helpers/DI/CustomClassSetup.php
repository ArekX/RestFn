<?php
/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

namespace tests\Helpers\DI;


class CustomClassSetup extends CustomClass
{
    public $setup;

    public function __construct(array $setup)
    {
        $this->setup = $setup;
    }
}