<?php

namespace tests\Validation\Fields;

use ArekX\JsonQL\Helpers\DI;
use ArekX\JsonQL\Validation\Fields\AllOfField;
use ArekX\JsonQL\Validation\Fields\AnyOfField;
use tests\Validation\Mocks\MockField;

/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/
class AnyOfFieldTest extends \tests\TestCase
{
    public function testAllOfFieldAcceptsInterfaces()
    {
        $dummyFields = [
            new MockField(),
            new MockField(),
            new MockField(),
        ];

        $allOfField = $this->createField($dummyFields);
        $this->assertEquals($allOfField->fields, $dummyFields);
    }

    public function testAddingAFieldAppendsToList()
    {
        $dummyFields = [
            new MockField(),
            new MockField(),
            new MockField(),
        ];

        $allOfField = $this->createField($dummyFields);
        $this->assertEquals($allOfField->fields, $dummyFields);

        $newField = new MockField();
        $dummyFields[] = $newField;
        $allOfField->andField($newField);

        $this->assertEquals($allOfField->fields, $dummyFields);
    }

    public function testAddingMultipleFieldsAddsThemToList()
    {
        $dummyFields = [
            new MockField(),
            new MockField(),
            new MockField(),
        ];

        $allOfField = $this->createField($dummyFields);
        $this->assertEquals($allOfField->fields, $dummyFields);

        $newFields = [
            new MockField(),
            new MockField(),
            new MockField(),
        ];
        $dummyFields = array_merge($dummyFields, $newFields);

        $this->assertSame($allOfField, $allOfField->withFields($newFields));
        $this->assertEquals($allOfField->fields, $dummyFields);
    }

    public function testCallingAddReturnsChainedInterface()
    {
        $allOfField = $this->createField();
        $this->assertSame($allOfField, $allOfField->andField(new MockField()));
    }

    public function testCallingValidateOneDummyField()
    {
        $allOfField = $this->createField([new MockField()]);
        $result = $allOfField->validate('fieldName', 'value');
        $this->assertEmpty($result);
    }

    public function testCallingValidateOnZeroFields()
    {
        $allOfField = $this->createField([]);
        $this->assertEmpty($allOfField->validate('fieldName', rand(1, 500)));
        $this->assertEmpty($allOfField->validate('fieldName', rand(1, 500)));
        $this->assertEmpty($allOfField->validate('fieldName', rand(1, 500)));
        $this->assertEmpty($allOfField->validate('fieldName', rand(1, 500)));
    }

    public function testAllOfCallsValidateOfOtherFields()
    {
        $field = $this->createMock(MockField::class);

        $field
            ->expects($this->once())
            ->method('validate')
            ->willReturn([]);

        $allOfField = $this->createField([$field]);
        $this->assertEmpty($allOfField->validate('fieldName', rand(1, 500)));
    }

    public function testFirstSuccessWontValidateSecond()
    {
        $field1 = $this->createMock(MockField::class);
        $field2 = $this->createMock(MockField::class);

        $field1->method('validate')->willReturn([]);
        $field2->expects($this->never())->method('validate')->willReturn(['error2']);

        $allOfField = $this->createField([$field1, $field2]);
        $this->assertEquals($allOfField->validate('fieldName', rand(1, 500)), []);
    }

    public function testWillRunUntilItSucceeds()
    {
        $field1 = $this->createMock(MockField::class);
        $field2 = $this->createMock(MockField::class);

        $field1->method('validate')->willReturn(['error1']);
        $field2->method('validate')->willReturn(['error2']);

        $allOfField = $this->createField([$field1, $field2]);
        $this->assertEquals($allOfField->validate('fieldName', rand(1, 500)), ['error1', 'error2']);
    }

    public function testSettingRequired()
    {
        $field = $this->createField();
        $this->assertFalse($field->isRequired);
        $this->assertSame($field->required(), $field);
        $this->assertTrue($field->isRequired);
    }

    public function testSettingRequiredToNonRequired()
    {
        $field = $this->createField();
        $this->assertSame($field->required(), $field);
        $this->assertTrue($field->isRequired);
        $this->assertSame($field->required(false), $field);
        $this->assertFalse($field->isRequired);
    }

    public function testDoesNotRunIfNotRequiredAndEmptyValue()
    {
        $field1 = $this->createMock(MockField::class);
        $field2 = $this->createMock(MockField::class);

        $field1->method('validate')->willReturn(['error1']);
        $field2->method('validate')->willReturn(['error2']);

        $field = $this->createField([$field1, $field2]);
        $field->required(false);
        $this->assertEquals($field->validate('fieldName', null), []);
    }


    public function testCanSetEmptyValue()
    {
        $field = $this->createField();
        $this->assertNull($field->emptyValue);
        $this->assertSame($field->emptyValue(""), $field);
        $this->assertEquals($field->emptyValue, "");
    }

    public function testSetEmptyValueIsChecked()
    {
        $field1 = $this->createMock(MockField::class);
        $field2 = $this->createMock(MockField::class);

        $field1->method('validate')->willReturn(['error1']);
        $field2->method('validate')->willReturn(['error2']);

        $field = $this->createField([$field1, $field2]);

        $field->required(false);
        $this->assertEquals($field->validate('fieldName', []), ['error1', 'error2']);
        $field->emptyValue([]);
        $this->assertEquals($field->validate('fieldName', []), []);
    }

    public function testDefinitionIsReturned()
    {
        $field = $this->createField();

        $this->assertEquals([
            'type' => 'anyOf',
            'emptyValue' => null,
            'required' => false,
            'fields' => []
        ], $field->definition());
    }

    public function testAddedFieldsAreInDefinition()
    {
        $field = $this->createField([
            new MockField([], ['type' => 'mock1']),
            new MockField([], ['type' => 'mock2'])
        ]);

        $this->assertEquals([
            'type' => 'anyOf',
            'emptyValue' => null,
            'required' => false,
            'fields' => [
                [
                    'type' => 'mock1',
                    'emptyValue' => null,
                    'required' => false,
                ],
                [
                    'type' => 'mock2',
                    'emptyValue' => null,
                    'required' => false,
                ]
            ]
        ], $field->definition());
    }

    protected function createField(array $dummyFields = []): AnyOfField
    {
        return DI::make(\ArekX\JsonQL\Validation\Fields\AnyOfField::class, [
            'fields' => $dummyFields
        ]);
    }
}