<?php
/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license: http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

namespace ArekX\JsonQL\Types;

use function ArekX\JsonQL\Validation\fromType;
use function ArekX\JsonQL\Validation\objectField;
use function ArekX\JsonQL\Validation\stringField;

class RequestSingleItem extends BaseType
{
    /**
     * @inheritdoc
     */
    public static function typeName(): string
    {
        return 'single-request';
    }

    /**
     * @inheritdoc
     */
    public static function fields(): array
    {
        return [
            'as' => stringField()->info('Name of the result set. Will be returned in response.'),
            'at' => objectField()->info('Request parameters.'),
            'fields' => fromType(FieldItems::class)
        ];
    }

    protected static function requiredKeys()
    {
        return [];
    }
}