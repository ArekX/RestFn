<?php
/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

namespace ArekX\JsonQL\Validation;

use ArekX\JsonQL\Helpers\DI;
use ArekX\JsonQL\Types\BaseType;
use ArekX\JsonQL\Types\ObjectType;
use ArekX\JsonQL\Validation\Fields\AllOfField;
use ArekX\JsonQL\Validation\Fields\AnyField;
use ArekX\JsonQL\Validation\Fields\AnyOfField;
use ArekX\JsonQL\Validation\Fields\ArrayField;
use ArekX\JsonQL\Validation\Fields\BoolField;
use ArekX\JsonQL\Validation\Fields\CompareField;
use ArekX\JsonQL\Validation\Fields\EnumField;
use ArekX\JsonQL\Validation\Fields\NullField;
use ArekX\JsonQL\Validation\Fields\NumberField;
use ArekX\JsonQL\Validation\Fields\ObjectField;
use ArekX\JsonQL\Validation\Fields\RecursiveField;
use ArekX\JsonQL\Validation\Fields\StringField;

if (!function_exists('ArekX\JsonQL\Validation\allOf')) {

    /**
     * Creates new AllOFField instance to validate all of fields
     *
     * @see AllOfField
     * @param FieldInterface ...$fields Fields to be added in AllOfField instance.
     * @return AllOfField New instance of AllOfField
     * @throws \Auryn\InjectionException
     */
    function allOf(FieldInterface ...$fields): AllOfField
    {
        return DI::make(AllOfField::class, [
            ':fields' => $fields
        ]);
    }
}


if (!function_exists('ArekX\JsonQL\Validation\anyOf')) {

    /**
     * Creates new AnyOfField instance to validate for any of specified fields
     *
     * @see AnyOfField
     * @param FieldInterface ...$fields Fields to be added in AnyOfField instance.
     * @return AnyOfField New instance of AnyOfField
     * @throws \Auryn\InjectionException
     */
    function anyOf(FieldInterface ...$fields): AnyOfField
    {
        return DI::make(AnyOfField::class, [
            ':fields' => $fields
        ]);
    }
}


if (!function_exists('ArekX\JsonQL\Validation\compare')) {

    /**
     * Creates new CompareField instance
     *
     * @see CompareField
     * @return CompareField New instance of CompareField
     * @throws \Auryn\InjectionException
     */
    function compare(): CompareField
    {
        return DI::make(CompareField::class);
    }
}

if (!function_exists('ArekX\JsonQL\Validation\numberField')) {

    /**
     * Creates new NumberField instance
     *
     * @see NumberField
     * @return NumberField New instance of NumberField
     * @throws \Auryn\InjectionException
     */
    function numberField(): NumberField
    {
        return DI::make(NumberField::class);
    }
}

if (!function_exists('ArekX\JsonQL\Validation\stringField')) {

    /**
     * Creates new StringField instance
     *
     * @see StringField
     * @param int|null $length Maximum length of the string.
     * @return StringField New instance of StringField
     * @throws \Auryn\InjectionException
     */
    function stringField(?int $length = null): StringField
    {
        return DI::make(StringField::class, [':maxLength' => $length]);
    }
}


if (!function_exists('ArekX\JsonQL\Validation\anyField')) {

    /**
     * Creates new AnyField instance
     *
     * @see AnyField
     * @return AnyField New instance of AnyField
     * @throws \Auryn\InjectionException
     */
    function anyField(): AnyField
    {
        return DI::make(AnyField::class);
    }
}

if (!function_exists('ArekX\JsonQL\Validation\arrayField')) {

    /**
     * Creates new ArrayField instance
     *
     * @see ArrayField
     * @return ArrayField New instance of ArrayField
     * @throws \Auryn\InjectionException
     */
    function arrayField(): ArrayField
    {
        return DI::make(ArrayField::class);
    }
}

if (!function_exists('ArekX\JsonQL\Validation\boolField')) {

    /**
     * Creates new BoolField instance
     *
     * @see BoolField
     * @return BoolField New instance of BoolField
     * @throws \Auryn\InjectionException
     */
    function boolField(): BoolField
    {
        return DI::make(BoolField::class);
    }
}

if (!function_exists('ArekX\JsonQL\Validation\nullField')) {

    /**
     * Creates new NullField instance
     *
     * @see NullField
     * @return NullField New instance of NullField
     * @throws \Auryn\InjectionException
     */
    function nullField(): NullField
    {
        return DI::make(NullField::class);
    }
}


if (!function_exists('ArekX\JsonQL\Validation\enumField')) {

    /**
     * Creates new EnumField instance
     *
     * @see EnumField
     * @param array $values
     * @return EnumField New instance of EnumField
     * @throws \Auryn\InjectionException
     */
    function enumField(array $values): EnumField
    {
        return DI::make(EnumField::class, [':values' => $values]);
    }
}

if (!function_exists('ArekX\JsonQL\Validation\objectField')) {

    /**
     * Creates new ObjectField instance
     *
     * @see ObjectField
     * @param array $fields Fields to be passed to constructor.
     * @return ObjectField New instance of ObjectField
     * @throws \Auryn\InjectionException
     */
    function objectField(array $fields = []): ObjectField
    {
        return DI::make(ObjectField::class, [':fields' => $fields]);
    }
}

if (!function_exists('ArekX\JsonQL\Validation\fromType')) {

    /**
     * Creates ObjectField from TypeInterface.
     *
     * @param string $className Class implementing TypeInterface from which the fields will be taken.
     * @param array $mergeFields Fields which will be merged with type fields. Existing fields will be overridden.
     * @return ObjectField|FieldInterface Object field created from type.
     * @throws \Auryn\InjectionException
     */
    function fromType($className, $mergeFields = [])
    {
        /** @var $className BaseType|ObjectType */
        $fields = $className::fields();

        if ($fields instanceof FieldInterface) {
            return $fields;
        }

        return objectField($fields)
            ->typeName($className::typeName())
            ->merge($mergeFields);
    }
}

if (!function_exists('ArekX\JsonQL\Validation\recursiveField')) {

    /**
     * Creates RecursiveField for marking fields as recursive.
     *
     * This field will behave same as the original field in terms of validation,
     * but it will return recursive definition to prevent infinite loops.
     *
     * @param FieldInterface $field Field which is wrapped as recursive.
     * @return RecursiveField
     * @throws \Auryn\InjectionException
     */
    function recursiveField(FieldInterface $field): RecursiveField
    {
        return DI::make(RecursiveField::class, [':field' => $field]);
    }
}