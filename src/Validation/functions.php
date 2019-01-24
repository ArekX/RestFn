<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Validation;

use ArekX\JsonQL\Validation\Rules\AnyRule;
use ArekX\JsonQL\Validation\Rules\ArrayRule;
use ArekX\JsonQL\Validation\Rules\BoolRule;
use ArekX\JsonQL\Validation\Rules\NullRule;
use ArekX\JsonQL\Validation\Rules\ObjectRule;
use ArekX\JsonQL\Validation\Rules\SubTypeRule;
use ArekX\JsonQL\Validation\Rules\AndRule;
use ArekX\JsonQL\Validation\Rules\CompareRule;
use ArekX\JsonQL\Validation\Rules\EnumRule;
use ArekX\JsonQL\Validation\Rules\NumberRule;
use ArekX\JsonQL\Validation\Rules\OrRule;
use ArekX\JsonQL\Validation\Rules\StringRule;

if (!function_exists('ArekX\JsonQL\Validation\stringType')) {
    function stringType(): StringRule
    {
        return new StringRule();
    }
}

if (!function_exists('ArekX\JsonQL\Validation\compareType')) {
    function compareType(): CompareRule
    {
        return new CompareRule();
    }
}


if (!function_exists('ArekX\JsonQL\Validation\andType')) {
    function andType(...$rules): AndRule
    {
        return new AndRule($rules);
    }
}

if (!function_exists('ArekX\JsonQL\Validation\orType')) {
    function orType(...$rules): OrRule
    {
        return new OrRule($rules);
    }
}

if (!function_exists('ArekX\JsonQL\Validation\anyType')) {
    function anyType(): AnyRule
    {
        return new AnyRule();
    }
}

if (!function_exists('ArekX\JsonQL\Validation\objectType')) {
    function objectType($fields): ObjectRule
    {
        return new ObjectRule($fields);
    }
}

if (!function_exists('ArekX\JsonQL\Validation\numberType')) {
    function numberType(): NumberRule
    {
        return new NumberRule();
    }
}

if (!function_exists('ArekX\JsonQL\Validation\nullType')) {
    function nullType(): NullRule
    {
        return new NullRule();
    }
}

if (!function_exists('ArekX\JsonQL\Validation\enumType')) {
    function enumType(array $enum): EnumRule
    {
        return new EnumRule($enum);
    }
}

if (!function_exists('ArekX\JsonQL\Validation\subType')) {
    function subType($subType): SubTypeRule
    {
        return new SubTypeRule($subType);
    }
}

if (!function_exists('ArekX\JsonQL\Validation\arrayType')) {
    function arrayType(): ArrayRule
    {
        return new ArrayRule();
    }
}


if (!function_exists('ArekX\JsonQL\Validation\boolType')) {
    function boolType(): BoolRule
    {
        return new BoolRule();
    }
}