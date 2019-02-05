<?php
/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license: http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

namespace tests\Validation\Fields;

use ArekX\JsonQL\Helpers\DI;
use function ArekX\JsonQL\Validation\anyOf;
use function ArekX\JsonQL\Validation\arrayField;
use ArekX\JsonQL\Validation\BaseField;
use ArekX\JsonQL\Validation\FieldInterface;
use ArekX\JsonQL\Validation\Fields\ArrayField;
use ArekX\JsonQL\Validation\Fields\RecursiveField;
use ArekX\JsonQL\Validation\Fields\StringField;
use function ArekX\JsonQL\Validation\objectField;
use function ArekX\JsonQL\Validation\recursiveField;
use function ArekX\JsonQL\Validation\stringField;
use tests\Validation\Mocks\MockField;

class RecursiveFieldTest extends \tests\TestCase
{
    public function testRecursiveFieldDefinition()
    {
        $arrayField = arrayField()->identifier('array_field');
        $oneOf = anyOf(
            stringField(100),
            recursiveField($arrayField)
        );

        $arrayField->of($oneOf);

        $this->assertEquals([
            'type' => 'array',
            'identifier' => 'array_field',
            'info' => null,
            'example' => null,
            'notEmpty' => false,
            'emptyValue' => null,
            'itemType' => [
                'type' => 'anyOf',
                'identifier' => null,
                'info' => null,
                'example' => null,
                'notEmpty' => false,
                'emptyValue' => null,
                'fields' => [
                    [
                        'type' => 'string',
                        'identifier' => null,
                        'info' => null,
                        'example' => null,
                        'notEmpty' => false,
                        'emptyValue' => null,
                        'maxLength' => 100,
                        'minLength' => null,
                        'match' => null,
                        'encoding' => null
                    ],
                    [
                        'type' => 'recursive',
                        'of' => 'array_field',
                        'identifier' => null
                    ]
                ]
            ]
        ], $arrayField->definition());
    }

    public function testRecursiveFieldCanValidate()
    {
        $field = arrayField()->identifier('array_field');
        $oneOf = anyOf(
            stringField(100),
            recursiveField($field)
        );
        $field->of($oneOf);

        $this->assertEquals([], $field->validate(['string']));
    }

    public function testRecursiveFieldCanValidateRecursively()
    {
        $field = arrayField()->identifier('array_field');
        $oneOf = anyOf(
            stringField(100),
            recursiveField($field)
        );
        $field->of($oneOf);

        $this->assertEquals([], $field->validate([
            [
                [
                    [
                        [
                            [
                                [
                                    'string'
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]));
    }

    public function testFailsToValidate()
    {
        $field = arrayField()->identifier('array_field');
        $oneOf = anyOf(
            stringField(100),
            recursiveField($field)
        );
        $field->of($oneOf);

        $this->assertEquals([
            [
                'type' => ArrayField::ERROR_ITEM_NOT_VALID,
                'items' => [
                    [
                        ['type' => StringField::ERROR_NOT_A_STRING],
                        [
                            'type' => ArrayField::ERROR_ITEM_NOT_VALID,
                            'items' => [
                                [
                                    ['type' => StringField::ERROR_NOT_A_STRING],
                                    ['type' => ArrayField::ERROR_NOT_AN_ARRAY]
                                ]
                            ]
                        ]
                    ],
                ]
            ]
        ], $field->validate([
            [
                1
            ]
        ]));
    }


    protected function createField(FieldInterface $field): RecursiveField
    {
        return recursiveField($field);
    }
}