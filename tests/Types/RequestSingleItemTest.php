<?php

namespace tests\Types;

use ArekX\JsonQL\Types\RequestPagedItems;
use ArekX\JsonQL\Types\RequestSingleItem;
use function ArekX\JsonQL\Validation\objectField;

/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license: http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/
class RequestSingleItemTest extends \tests\TestCase
{
    public function testHasValidType()
    {
        $fields = objectField(RequestSingleItem::fields())
            ->typeName(RequestSingleItem::typeName())
            ->requiredKeys(RequestSingleItem::requiredKeys());

        $this->assertEquals($fields->definition(), RequestSingleItem::definition());
        $this->assertEquals($fields->definition(), RequestSingleItem::definition());
    }
}