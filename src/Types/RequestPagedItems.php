<?php
/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

namespace ArekX\JsonQL\Types;

use function ArekX\JsonQL\Validation\arrayField;
use function ArekX\JsonQL\Validation\enumField;
use function ArekX\JsonQL\Validation\fromType;
use function ArekX\JsonQL\Validation\numberField;
use function ArekX\JsonQL\Validation\objectField;
use function ArekX\JsonQL\Validation\stringField;

class RequestPagedItems extends ObjectType
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
                'page' => numberField()->min(0),
                'size' => numberField()->min(1)->max(50)
            ])->requiredKeys(['page']),
            'sort' => arrayField()->of(objectField([
                'by' => stringField(),
                'direction' => enumField(['ascending', 'descending'])
            ])->requiredKeys(['by', 'direction'])),
            'fields' => FieldItems::field()
        ];
    }

    public static function requiredKeys()
    {
        return [];
    }
}