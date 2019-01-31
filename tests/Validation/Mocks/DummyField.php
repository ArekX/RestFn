<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace tests\Validation\Mocks;

use ArekX\JsonQL\Validation\FieldInterface;

class DummyField implements FieldInterface
{
    public $returns;

    public static function returns($value)
    {
        return new static($value);
    }

    public function __construct($value = [])
    {
        $this->returns = $value;
    }

    /**
     * Validates one fields value using this validator.
     *
     * @param string $field Name of the field to be validated.
     * @param mixed $value Value to be validated.
     * @return array List of failed validations for this field.
     */
    public function validate(string $field, $value)
    {
        return $this->returns;
    }
}
