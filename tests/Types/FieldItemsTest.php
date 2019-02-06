<?php

namespace tests\Types;

use ArekX\JsonQL\Types\FieldItems;

/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license: http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/
class FieldItemsTest extends \tests\TestCase
{
    public function testHasValidType()
    {
        $fields = FieldItems::fields();
        $this->assertEquals($fields->definition(), FieldItems::definition());
        $this->assertEquals($fields->definition(), FieldItems::definition());
    }
}