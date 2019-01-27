<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Validation;

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
use ArekX\JsonQL\Validation\Fields\OneOfField;
use ArekX\JsonQL\Validation\Fields\StringField;

if (!function_exists('ArekX\JsonQL\Validation\stringType')) {
    function stringType(): StringField
    {
        return new StringField();
    }
}

if (!function_exists('ArekX\JsonQL\Validation\compareType')) {
    function compareType(): CompareField
    {
        return new CompareField();
    }
}

if (!function_exists('ArekX\JsonQL\Validation\allOfType')) {
    function allOfType(...$fields): AllOfField
    {
        return new AllOfField($fields);
    }
}

if (!function_exists('ArekX\JsonQL\Validation\oneOf')) {
    function oneOf(...$fields): OneOfField
    {
        return new OneOfField($fields);
    }
}

if (!function_exists('ArekX\JsonQL\Validation\anyType')) {
    function anyType(): AnyField
    {
        return new AnyField();
    }
}

if (!function_exists('ArekX\JsonQL\Validation\objectType')) {
    function objectType(): ObjectField
    {
        return new ObjectField();
    }
}

if (!function_exists('ArekX\JsonQL\Validation\numberType')) {
    function numberType(): NumberField
    {
        return new NumberField();
    }
}

if (!function_exists('ArekX\JsonQL\Validation\nullType')) {
    function nullType(): NullField
    {
        return new NullField();
    }
}

if (!function_exists('ArekX\JsonQL\Validation\enumType')) {
    function enumType(array $enum): EnumField
    {
        return new EnumField($enum);
    }
}

if (!function_exists('ArekX\JsonQL\Validation\classType')) {
    function classType($typeClass): ClassTypeField
    {
        return new ClassTypeField($typeClass);
    }
}

if (!function_exists('ArekX\JsonQL\Validation\arrayType')) {
    function arrayType(): ArrayField
    {
        return new ArrayField();
    }
}


if (!function_exists('ArekX\JsonQL\Validation\boolType')) {
    function boolType(): BoolField
    {
        return new BoolField();
    }
}