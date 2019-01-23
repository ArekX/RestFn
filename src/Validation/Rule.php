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

class Rule
{
    public static function string(): StringRule
    {
        return new StringRule();
    }

    public static function number()
    {

    }

    public static function bool()
    {

    }

    public static function enum()
    {

    }

    public static function array()
    {

    }

    public static function type()
    {

    }

    public static function oneOf()
    {

    }

    public static function mixed()
    {

    }

    public static function compare(): CompareRule
    {
        return new CompareRule();
    }

    public static function and(...$rules): AndRule
    {
        return new AndRule($rules);
    }

    public static function or(...$rules): OrRule
    {
        return new OrRule($rules);
    }
}