<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Validation;

use ArekX\JsonQL\Helpers\DI;
use ArekX\JsonQL\Validation\Fields\AllOfField;
use ArekX\JsonQL\Validation\Fields\AnyOfField;
use ArekX\JsonQL\Validation\Fields\NumberField;

if (!function_exists('ArekX\JsonQL\Validation\allOf')) {

    /**
     * Creates new AllOFField instance to validate all of fields
     *
     * @see AllOfField
     * @param FieldInterface ...$fields Fields to be added in AllOfField instance.
     * @return AllOfField New instance of AllOfField
     */
    function allOf(FieldInterface ...$fields): AllOfField
    {
        return DI::make(AllOfField::class, [
            'fields' => $fields
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
     */
    function anyOf(FieldInterface ...$fields): AnyOfField
    {
        return DI::make(AnyOfField::class, [
            'fields' => $fields
        ]);
    }
}

if (!function_exists('ArekX\JsonQL\Validation\numberField')) {

    /**
     * Creates new NumberField instance to validate for any of specified fields
     *
     * @see NumberField
     * @return NumberField New instance of AnyOfField
     */
    function numberField(): NumberField
    {
        return DI::make(NumberField::class);
    }
}