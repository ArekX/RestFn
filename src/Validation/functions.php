<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Validation;

use ArekX\JsonQL\Helpers\DI;
use ArekX\JsonQL\Validation\Fields\AnyField;
use ArekX\JsonQL\Validation\Fields\ArrayField;
use ArekX\JsonQL\Validation\Fields\BoolField;
use ArekX\JsonQL\Validation\Fields\NullField;
use ArekX\JsonQL\Validation\Fields\ObjectField;
use ArekX\JsonQL\Validation\Fields\ClassTypeField;
use ArekX\JsonQL\Validation\Fields\AllOfField;
use ArekX\JsonQL\Validation\Fields\CompareField;
use ArekX\JsonQL\Validation\Fields\EnumField;
use ArekX\JsonQL\Validation\Fields\NumberField;
use ArekX\JsonQL\Validation\Fields\AnyOfField;
use ArekX\JsonQL\Validation\Fields\StringField;

if (!function_exists('ArekX\JsonQL\Validation\allOfFields')) {
    function allOfFields(FieldInterface ...$fields): AllOfField
    {
        return DI::make(AllOfField::class, [
            'fields' => $fields
        ]);
    }
}

if (!function_exists('ArekX\JsonQL\Validation\anyOfFields')) {
    function anyOfFields(...$fields): AnyOfField
    {
        return DI::make(AnyOfField::class, [
            'fields' => $fields
        ]);
    }
}

if (!function_exists('ArekX\JsonQL\Validation\stringField')) {
    function stringField(): StringField
    {
        return DI::make(StringField::class);
    }
}

if (!function_exists('ArekX\JsonQL\Validation\compareField')) {
    function compareField(): CompareField
    {
        return DI::make(CompareField::class);
    }
}

if (!function_exists('ArekX\JsonQL\Validation\anyField')) {
    function anyField(): AnyField
    {
        return DI::make(AnyField::class);
    }
}

if (!function_exists('ArekX\JsonQL\Validation\objectField')) {
    function objectField(): ObjectField
    {
        return DI::make(ObjectField::class);
    }
}

if (!function_exists('ArekX\JsonQL\Validation\numberField')) {
    function numberField(): NumberField
    {
        return DI::make(NumberField::class);
    }
}

if (!function_exists('ArekX\JsonQL\Validation\nullField')) {
    function nullField(): NullField
    {
        return DI::make(NullField::class);
    }
}

if (!function_exists('ArekX\JsonQL\Validation\enumField')) {
    function enumField(array $enum): EnumField
    {
        return DI::make(EnumField::class, ['values' => $enum]);
    }
}

if (!function_exists('ArekX\JsonQL\Validation\classField')) {
    function classField($typeClass): ClassTypeField
    {
        /** @var TypeInterface $typeClass */
        return DI::make(ClassTypeField::class, [
            'name' => $typeClass::name(),
            'fields' => $typeClass::fields()
        ]);
    }
}

if (!function_exists('ArekX\JsonQL\Validation\arrayType')) {
    function arrayType(): ArrayField
    {
        return DI::make(ArrayField::class);
    }
}


if (!function_exists('ArekX\JsonQL\Validation\boolField')) {
    function boolField(): BoolField
    {
        return DI::make(BoolField::class);
    }
}