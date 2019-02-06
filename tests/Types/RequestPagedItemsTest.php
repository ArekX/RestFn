<?php

namespace tests\Types;

use ArekX\JsonQL\Types\RequestPagedItems;
use function ArekX\JsonQL\Validation\objectField;

/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license: http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/
class RequestPagedItemsTest extends \tests\TestCase
{
    public function testHasValidType()
    {
        $fields = objectField(RequestPagedItems::fields())
            ->typeName(RequestPagedItems::typeName())
            ->requiredKeys(RequestPagedItems::requiredKeys());

        $this->assertEquals($fields->definition(), RequestPagedItems::definition());
        $this->assertEquals($fields->definition(), RequestPagedItems::definition());
    }
}