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
use ArekX\JsonQL\Validation\Fields\BoolField;
use ArekX\JsonQL\Validation\Fields\NullField;
use ArekX\JsonQL\Validation\Fields\NumberField;
use ArekX\JsonQL\Validation\Fields\StringField;

if (!function_exists('ArekX\JsonQL\Validation\allOf')) {

    /**
     * Creates new AllOFField instance to validate all of fields
     *
     * @see AllOfField
     * @param FieldInterface ...$fields Fields to be added in AllOfField instance.
     * @return AllOfField New instance of AllOfField
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
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
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
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
     * Creates new NumberField instance
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
     * Creates new StringField instance
     *
     * @see StringField
     * @param int|null $length
     * @return StringField New instance of StringField
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    function stringField(?int $length = null): StringField
    {
        return DI::make(StringField::class, ['length' => $length]);
    }
}


if (!function_exists('ArekX\JsonQL\Validation\anyField')) {

    /**
     * Creates new AnyField instance
     *
     * @see AnyField
     * @return AnyField New instance of AnyField
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
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
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
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
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
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
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    function nullField(): NullField
    {
        return DI::make(NullField::class);
    }
}