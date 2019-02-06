<?php
/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license: http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

namespace ArekX\JsonQL\Types;

use ArekX\JsonQL\Validation\FieldInterface;

use function ArekX\JsonQL\Validation\anyOf;
use function ArekX\JsonQL\Validation\arrayField;
use function ArekX\JsonQL\Validation\objectField;
use function ArekX\JsonQL\Validation\recursiveField;
use function ArekX\JsonQL\Validation\stringField;


class FieldItems extends BaseType
{
    /**
     * @inheritdoc
     */
    public static function fields(): FieldInterface
    {
        $fieldsType = arrayField()
            ->identifier('array_fields')
            ->info('Fields which will be returned')
            ->example([
                'field1',
                [
                    'for' => 'field2',
                    'as' => 'newFieldName',
                    'fields' => [
                        'subField1',
                        'subField2',
                    ]
                ]
            ]);

        return $fieldsType->of(anyOf(
            stringField()->info('Name of the field.'),
            objectField([
                'for' => stringField()->info('Name of the parent field which will be returned.'),
                'as' => stringField()->info('New name of the field.'),
                'fields' => recursiveField($fieldsType)
            ])->requiredKeys(['for'])->info('Name of the field with child fields.')));
    }

    /**
     * @inheritdoc
     */
    public static function typeName(): string
    {
        return 'field-items';
    }
}