<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Validation\Rules;


use ArekX\JsonQL\Helpers\Value;
use ArekX\JsonQL\Validation\RuleInterface;

/**
 * Class BaseRule
 * @package ArekX\JsonQL\Validation\Rules
 *
 */
abstract class BaseRule implements RuleInterface
{
    /** @var bool  */
    protected $required = false;

    /** @var bool  */
    protected $requiredStrict = false;

    /** @var string */
    protected $message;

    /** @var array */
    protected $example;

    /**
     * Returns whether or not field is required.
     *
     * @param bool $required
     * @param bool $strict
     * @return static
     */
    public function required($required = true, $strict = false): RuleInterface
    {
        $this->required = $required;
        $this->requiredStrict = $strict;
        return $this;
    }

    /**
     * Add information about the field.
     *
     * @param string $message
     * @return static
     */
    public function info(string $message, $example = []): RuleInterface
    {
        $this->message = $message;
        $this->example = $example;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function validate(string $field, $value, $data): array
    {
        $errors = [];

        if ($this->required || Value::isEmpty($value, $this->requiredStrict)) {
            $errors[] = ['type' => 'empty_value'];
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