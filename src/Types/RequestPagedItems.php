<?php
/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license: http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

namespace ArekX\JsonQL\Types;

use function ArekX\JsonQL\Validation\numberField;
use function ArekX\JsonQL\Validation\objectField;
use function ArekX\JsonQL\Validation\stringField;

class RequestPagedItems extends BaseType
{
    /**
     * @inheritdoc
     */
    public static function typeName(): string
    {
        return 'paged-request';
    }

    /**
     * @inheritdoc
     */
    public static function fields(): array
    {
        return [
            'as' => stringField(),
            'filter' => objectField(),
            'pagination' => objectField([
                'page' => numberField(),
                'size' => numberField()->min(1)->max(50)
            ]),
            'fields' => objectField()
        ];
    }

    protected static function requiredKeys()
    {
        return [];
    }
}