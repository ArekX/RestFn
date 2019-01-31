<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Validation;

/**
 * Interface FieldInterface
 * @package ArekX\JsonQL\Validation
 *
 * Field interface for all of the fields used for validation.
 */
interface FieldInterface
{
    /**
     * Validates one fields value using this validator.
     *
     * @param string $field Name of the field to be validated.
     * @param mixed $value Value to be validated.
     * @return array List of failed validations for this field.
     */
    public function validate(string $field, $value);
}