<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Validation;


interface RuleInterface
{
    /**
     * Specifies whether or not specific field is required.
     *
     * @param bool $required Required value.
     * @param bool $strict Whether or not to use strict checking for empty or just to use empty() in PHP.
     * @return static Instance of this field.
     */
    public function required($required = true, $strict = false): RuleInterface;

    /**
     * Adds rule information to be displayed.
     *
     * @param string $message Message to be added.
     * @return RuleInterface
     */
    public function info(string $message): RuleInterface;

    /**
     * Validates current field's whether or not it is valid.
     *
     * If returned value is an empty array then value is valid,
     * otherwise the returned value has errors.
     *
     * @param string $field Field name
     * @param mixed $value Value to be validated.
     * @param array $data All other data to be validated.
     * @return array
     */
    public function validate(string $field, $value, $data): array;
}