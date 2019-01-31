<?php

namespace tests\Validation\Fields;

use ArekX\JsonQL\Helpers\DI;
use ArekX\JsonQL\Validation\Fields\AllOfField;
use ArekX\JsonQL\Validation\Fields\AnyOfField;
use tests\Validation\Mocks\DummyField;

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
            new DummyField(),
            new DummyField(),
            new DummyField(),
        ];

        $allOfField = $this->createField($dummyFields);
        $this->assertEquals($allOfField->fields, $dummyFields);
    }

    public function testAddingAFieldAppendsToList()
    {
        $dummyFields = [
            new DummyField(),
            new DummyField(),
            new DummyField(),
        ];

        $allOfField = $this->createField($dummyFields);
        $this->assertEquals($allOfField->fields, $dummyFields);

        $newField = new DummyField();
        $dummyFields[] = $newField;
        $allOfField->andField($newField);

        $this->assertEquals($allOfField->fields, $dummyFields);
    }

    public function testAddingMultipleFieldsAddsThemToList()
    {
        $dummyFields = [
            new DummyField(),
            new DummyField(),
            new DummyField(),
        ];

        $allOfField = $this->createField($dummyFields);
        $this->assertEquals($allOfField->fields, $dummyFields);

        $newFields = [
            new DummyField(),
            new DummyField(),
            new DummyField(),
        ];
        $dummyFields = array_merge($dummyFields, $newFields);

        $this->assertSame($allOfField, $allOfField->withFields($newFields));
        $this->assertEquals($allOfField->fields, $dummyFields);
    }

    public function testCallingAddReturnsChainedInterface()
    {
        $allOfField = $this->createField();
        $this->assertSame($allOfField, $allOfField->andField(new DummyField()));
    }

    public function testCallingValidateOneDummyField()
    {
        $allOfField = $this->createField([new DummyField()]);
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
        $field = $this->createMock(DummyField::class);

        $field
            ->expects($this->once())
            ->method('validate')
            ->willReturn([]);

        $allOfField = $this->createField([$field]);
        $this->assertEmpty($allOfField->validate('fieldName', rand(1, 500)));
    }

    public function testFirstSuccessWontValidateSecond()
    {
        $field1 = $this->createMock(DummyField::class);
        $field2 = $this->createMock(DummyField::class);

        $field1->method('validate')->willReturn([]);
        $field2->expects($this->never())->method('validate')->willReturn(['error2']);

        $allOfField = $this->createField([$field1, $field2]);
        $this->assertEquals($allOfField->validate('fieldName', rand(1, 500)), []);
    }

    public function testWillRunUntilItSucceeds()
    {
        $field1 = $this->createMock(DummyField::class);
        $field2 = $this->createMock(DummyField::class);

        $field1->method('validate')->willReturn(['error1']);
        $field2->method('validate')->willReturn(['error2']);

        $allOfField = $this->createField([$field1, $field2]);
        $this->assertEquals($allOfField->validate('fieldName', rand(1, 500)), ['error1', 'error2']);
    }


    protected function createField(array $dummyFields = []): AnyOfField
    {
        return DI::make(\ArekX\JsonQL\Validation\Fields\AnyOfField::class, [
            'fields' => $dummyFields
        ]);
    }
}