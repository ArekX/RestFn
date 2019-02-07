<?php
/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

namespace ArekX\JsonQL\Types;

use function ArekX\JsonQL\Validation\anyOf;
use function ArekX\JsonQL\Validation\objectField;

class ReaderRequest extends ObjectType
{
    /**
     * @inheritdoc
     */
    public static function typeName(): string
    {
        return 'reader-request';
    }

    /**
     * @inheritdoc
     */
    public static function fields()
    {
        return objectField()->anyKey(anyOf(
            RequestSingleItem::field(),
            RequestPagedItems::field()
        ));
    }

    public static function requiredKeys()
    {
        return [];
    }
}