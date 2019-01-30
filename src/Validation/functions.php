<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Validation;

use ArekX\JsonQL\Helpers\DI;
use ArekX\JsonQL\Validation\Fields\AllOfField;

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