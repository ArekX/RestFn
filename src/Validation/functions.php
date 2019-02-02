<?php
/**
  * @author Aleksandar Panic
  * @link https://jsonql.readthedocs.io/
  * @license: http://www.apache.org/licenses/LICENSE-2.0
  * @since 1.0.0
 **/

namespace ArekX\JsonQL\Validation;

use ArekX\JsonQL\Helpers\DI;
use ArekX\JsonQL\Validation\Fields\AllOfField;
use ArekX\JsonQL\Validation\Fields\AnyField;
use ArekX\JsonQL\Validation\Fields\AnyOfField;
use ArekX\JsonQL\Validation\Fields\ArrayField;
use ArekX\JsonQL\Validation\Fields\NumberField;
use ArekX\JsonQL\Validation\Fields\StringField;

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
     * @return NumberField New instance of NumberField
     */
    function numberField(): NumberField
    {
        return DI::make(NumberField::class);
    }
}

if (!function_exists('ArekX\JsonQL\Validation\stringField')) {

    /**
     * Creates new StringField instance to validate for any of specified fields
     *
     * @see StringField
     * @return StringField New instance of StringField
     */
    function stringField(?int $length = null): StringField
    {
        return DI::make(StringField::class, ['length' => $length]);
    }
}


if (!function_exists('ArekX\JsonQL\Validation\anyField')) {

    /**
     * Creates new AnyField instance to validate for any of specified fields
     *
     * @see AnyField
     * @return AnyField New instance of AnyField
     */
    function anyField(): AnyField
    {
        return DI::make(AnyField::class);
    }
}

if (!function_exists('ArekX\JsonQL\Validation\arrayField')) {

    /**
     * Creates new ArrayField instance to validate for any of specified fields
     *
     * @see ArrayField
     * @return ArrayField New instance of ArrayField
     */
    function arrayField(): ArrayField
    {
        return DI::make(ArrayField::class);
    }
}