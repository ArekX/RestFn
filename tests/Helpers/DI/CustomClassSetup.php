<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
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