<?php

namespace tests\Validation\Fields;

use ArekX\JsonQL\Helpers\DI;
use ArekX\JsonQL\Validation\Fields\AllOfField;
use tests\Validation\Mocks\DummyField;

/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/
class AllOfFieldTest extends \tests\TestCase
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

    protected function createField(array $dummyFields = []): AllOfField
    {
        return DI::make(\ArekX\JsonQL\Validation\Fields\AllOfField::class, [
            'fields' => $dummyFields
        ]);
    }
}