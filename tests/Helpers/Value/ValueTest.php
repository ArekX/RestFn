<?php
/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

namespace tests\Helpers\Value;

use tests\TestCase;

class ValueTest extends TestCase
{
    use
        MergeFunction,
        GetFunction,
        SetupFunction,
        IsEmptyFunction,
        HasFunction
    ;
}