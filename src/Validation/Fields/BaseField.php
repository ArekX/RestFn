<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Validation\Fields;


use ArekX\JsonQL\Helpers\Value;
use ArekX\JsonQL\Validation\FieldInterface;

/**
 * Class BaseRule
 * @package ArekX\JsonQL\Validation\Rules
 *
 */
abstract class BaseField implements FieldInterface
{
    const EMPTY_VALUE = 'empty_value';

    /** @var bool */
    protected $required = false;

    /** @var bool */
    protected $requiredStrict = false;

    /** @var string */
    protected $message;

    /** @var array */
    protected $example;

    /** @var null */
    protected $default = null;

    /** @var bool */
    protected $strict = false;

    /**
     * Returns whether or not field is required.
     *
     * @param bool $required
     * @param bool $strict
     * @return static
     */
    public function required($required = true, $strict = false): FieldInterface
    {
        $this->required = $required;
        $this->requiredStrict = $strict;
        return $this;
    }

    /**
     * Set default value of the field.
     *
     * @param mixed $value
     * @return static
     */
    public function default($value): FieldInterface
    {
        $this->default = $value;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getDefaultValue()
    {
        return $this->default;
    }

    /**
     * Add information about the field.
     *
     * @param string $message
     * @return static
     */
    public function info(string $message, $example = null): FieldInterface
    {
        $this->message = $message;
        $this->example = $example;
        return $this;
    }

    /**
     * Whether to perform strict validation or not.
     *
     * @param bool $strict Whether or not to use strict checking for empty or just to use empty() in PHP.
     * @return static Instance of this field.
     */
    public function strict($strict = true): FieldInterface
    {
        $this->strict = $strict;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function validateField(string $field, $value, $data): array
    {
        $errors = [];

        if ($this->required && Value::isEmpty($value, $this->requiredStrict)) {
            $errors[] = ['type' => self::EMPTY_VALUE];
        }

        return $this->doValidate($field, $value, $data, $errors);
    }

    /**
     * @inheritdoc
     */
    public function validate($value): array
    {
        return $this->validateField('@', $value, ['@' => $value]);
    }

    /**
     * Returns array definition of this field.
     * @return array
     */
    public function getDefinition(): array
    {
        return Value::merge([
            'type' => $this->getName(),
            'required' => $this->required,
            'strict' => $this->strict,
            'default' => $this->default,
            'info' => [
                'message' => $this->message,
                'example' => $this->example
            ]
        ], $this->getFieldDefinition());
    }

    /**
     * Returns type name.
     * @return string
     */
    protected abstract function getName(): string;

    /**
     * Performs child field validation.
     *
     * @param string $field Field name
     * @param mixed $value Value to be validated.
     * @param array $data All other data to be validated.
     * @return array
     */
    protected abstract function doValidate(string $field, $value, $data, $errors): array;

    /**
     * Returns field definition.
     * @return array
     */
    protected abstract function getFieldDefinition(): array;

    /**
     * Returns a copy of this field.
     * @return static
     */
    public abstract function clone();

    /**
     * @param static $self
     */
    protected function setupClone($self)
    {
        $self->required = $this->required;
        $self->requiredStrict = $this->requiredStrict;
        $self->message = $this->message;
        $self->example = $this->example;
        $self->default = $this->default;
        $self->strict = $this->strict;
    }
}