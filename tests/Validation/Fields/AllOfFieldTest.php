<?php
/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license: http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

namespace tests\Validation\Fields;

use ArekX\JsonQL\Validation\BaseField;
use ArekX\JsonQL\Validation\Fields\AllOfField;
use tests\Validation\Mocks\MockField;

class AllOfFieldTest extends \tests\TestCase
{
    public function testInstanceOfBaseField()
    {
        $this->assertInstanceOf(BaseField::class, $this->createField());
    }

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
        $result = $allOfField->validate('value');
        $this->assertEmpty($result);
    }

    public function testCallingValidateOnZeroFields()
    {
        $allOfField = $this->createField([]);
        $this->assertEmpty($allOfField->validate(rand(1, 500)));
        $this->assertEmpty($allOfField->validate(rand(1, 500)));
        $this->assertEmpty($allOfField->validate(rand(1, 500)));
        $this->assertEmpty($allOfField->validate(rand(1, 500)));
    }

    public function testAllOfCallsValidateOfOtherFields()
    {
        $field = $this->createMock(MockField::class);

        $field
            ->expects($this->once())
            ->method('validate')
            ->willReturn([]);

        $allOfField = $this->createField([$field]);
        $this->assertEmpty($allOfField->validate(rand(1, 500)));
    }

    public function testErrorsAreReturned()
    {
        $field1 = $this->createMock(MockField::class);
        $field2 = $this->createMock(MockField::class);

        $field1->method('validate')->willReturn([]);
        $field2->method('validate')->willReturn(['error2']);

        $allOfField = $this->createField([$field1, $field2]);
        $this->assertEquals($allOfField->validate(rand(1, 500)), ['error2']);
    }

    public function testFirstFailWontRunOtherValidators()
    {
        $field1 = $this->createMock(MockField::class);
        $field2 = $this->createMock(MockField::class);

        $field1->method('validate')->willReturn(['error1']);
        $field2->method('validate')->willReturn(['error2']);

        $allOfField = $this->createField([$field1, $field2]);
        $this->assertEquals($allOfField->validate(rand(1, 500)), ['error1']);
    }

    public function testValueIsPassedToAllFields()
    {
        $field1 = $this->createMock(MockField::class);
        $field2 = $this->createMock(MockField::class);

        $fieldValue = ['fieldValue'];
        $parentFieldValue = ['parentFieldValue'];

        $field2->method('validate')->with($fieldValue, $parentFieldValue)->willReturn([]);
        $field1->method('validate')->with($fieldValue, $parentFieldValue)->willReturn([]);

        $allOfField = $this->createField([$field1, $field2]);
        $this->assertEquals($allOfField->validate($fieldValue, $parentFieldValue), []);
    }

    public function testDefinitionIsReturned()
    {
        $field = $this->createField();

        $this->assertEquals([
            'type' => 'allOf',
            'info' => null,
            'example' => null,
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
            'type' => 'allOf',
            'emptyValue' => null,
            'info' => null,
            'example' => null,
            'required' => false,
            'fields' => [
                [
                    'type' => 'mock1',
                    'emptyValue' => null,
                    'info' => null,
                    'example' => null,
                    'required' => false,
                ],
                [
                    'type' => 'mock2',
                    'emptyValue' => null,
                    'info' => null,
                    'example' => null,
                    'required' => false,
                ]
            ]
        ], $field->definition());
    }

    protected function createField(array $dummyFields = []): AllOfField
    {
        return \ArekX\JsonQL\Validation\allOf(...$dummyFields);
    }
}