<?php
/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

namespace tests\Helpers\DI;



use tests\TestCase;

class DITest extends TestCase
{
    use
        MakeFunction,
        WireClassFunction
        ;
}