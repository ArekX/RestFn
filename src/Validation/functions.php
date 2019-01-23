<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Validation;

use ArekX\JsonQL\Validation\Rules\AndRule;
use ArekX\JsonQL\Validation\Rules\CompareRule;
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


if (!function_exists('ArekX\JsonQL\Validation\andFor')) {
    function andFor(...$rules): AndRule
    {
        return new AndRule($rules);
    }
}

if (!function_exists('ArekX\JsonQL\Validation\orFor')) {
    function orFor(...$rules): OrRule
    {
        return new OrRule($rules);
    }
}