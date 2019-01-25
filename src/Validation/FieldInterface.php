<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Validation;


interface FieldInterface
{
    /**
     * Specifies whether or not specific field is required.
     *
     * @param bool $required Required value.
     * @param bool $strict Whether or not to use strict checking for empty or just to use empty() in PHP.
     * @return static Instance of this field.
     */
    public function required($required = true, $strict = false): FieldInterface;


    /**
     * Default value to be set.
     * @param mixed $value Mixed value to be set.
     * @return static Instance of this field.
     */
    public function default($value): FieldInterface;

    /**
     * Returns set default value.
     *
     * @return mixed
     */
    public function getDefaultValue();

    /**
     * Returns array definition of this field.
     * @return array
     */
    public function getDefinition(): array;

    /**
     * Whether to perform strict validation or not.
     *
     * @param bool $strict Whether or not to use strict checking for empty or just to use empty() in PHP.
     * @return static Instance of this field.
     */
    public function strict($strict = true): FieldInterface;

    /**
     * Adds rule information to be displayed.
     *
     * @param string $message Message to be added.
     * @param array $example Example used to specify the type better.
     * @return FieldInterface
     */
    public function info(string $message, $example = []): FieldInterface;

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
    public function validateField(string $field, $value, $data): array;

    /**
     * Validates value whether or not it is valid.
     *
     * If returned value is an empty array then value is valid,
     * otherwise the returned value has errors.
     *
     * @param mixed $value Value to be validated.
     * @return array
     */
    public function validate($value): array;
}