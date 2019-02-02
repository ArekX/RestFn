<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Validation;


use ArekX\JsonQL\Helpers\Value;

abstract class BaseField implements FieldInterface
{
    /**
     * Whether or not this field is required.
     * Defaults to false.
     *
     * @var bool
     */
    public $isRequired = false;

    /**
     * Value which will be treated as an empty value for required check.
     *
     * Defaults to null
     *
     * @var mixed
     */
    public $emptyValue = null;

    /**
     * @inheritdoc
     */
    public function required(bool $isRequired = true)
    {
        $this->isRequired = $isRequired;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function emptyValue($emptyValue = null)
    {
        $this->emptyValue = $emptyValue;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function validate(string $field, $value, $parentValue = null): array
    {
        if (!$this->isRequired && $value === $this->emptyValue) {
            return [];
        }

        return $this->doValidate($field, $value, $parentValue);
    }

    /**
     * @inheritdoc
     */
    public function definition(): array
    {
        return Value::merge([
            'type' => $this->name(),
            'required' => $this->isRequired,
            'emptyValue' => $this->emptyValue
        ], $this->fieldDefinition());
    }

    /**
     * Returns name of this field.
     *
     * @return string
     */
    public abstract function name(): string;

    /**
     * Returns field definition.
     *
     * @return array
     */
    protected abstract function fieldDefinition(): array;

    /**
     * Performs actual fields validation.
     *
     * If a field if not required and has an empty value. This validation is not exectued.
     *
     * @param string $field Field name to be validated.
     * @param mixed $value Value to be validated.
     * @param mixed $parentValue Parent value this field and value is in if applicable.
     * @return array List of errors if not valid or an empty array if it is valid.
     */
    protected abstract function doValidate(string $field, $value, $parentValue = null): array;
}