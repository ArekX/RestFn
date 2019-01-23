<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Validation\Rules;


use ArekX\JsonQL\Helpers\Value;
use ArekX\JsonQL\Validation\RuleInterface;

abstract class BaseRule implements RuleInterface
{
    protected $required = false;
    protected $requiredStrict = false;
    protected $message;

    public function required($required = true, $strict = false): RuleInterface
    {
        $this->required = $required;
        $this->requiredStrict = $strict;
        return $this;
    }

    public function info(string $message): RuleInterface
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function validate(string $field, $value, $data): array
    {
        $errors = [];

        if ($this->required || Value::isEmpty($value, $this->requiredStrict)) {
            $errors[] = 'Value is empty.';
        }

        return $this->doValidate($field, $value, $data, $errors);
    }

    /**
     * Performs child field validation.
     *
     * @param string $field Field name
     * @param mixed $value Value to be validated.
     * @param array $data All other data to be validated.
     * @return array
     */
    protected abstract function doValidate(string $field, $value, $data, $errors): array;
}