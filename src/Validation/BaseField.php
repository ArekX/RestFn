<?php
/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license: http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

namespace ArekX\JsonQL\Validation;

use ArekX\JsonQL\Helpers\Value;

/**
 * Class BaseField
 * @package ArekX\JsonQL\Validation
 *
 * Base abstract field for all of the fields.
 */
abstract class BaseField implements FieldInterface, DefineFieldInterface
{
    const ERROR_VALUE_IS_EMPTY = 'value_is_empty';

    /**
     * Whether or not this field is required.
     * Defaults to false.
     *
     * @var bool
     */
    public $allowEmpty = false;

    /**
     * Value which will be treated as an empty value for required check.
     *
     * Defaults to null
     *
     * @var mixed
     */
    public $emptyValue = null;

    /**
     * Example for this field.
     *
     * Defaults to null - no example set
     *
     * @var mixed
     */
    public $example = null;

    /**
     * Information about this field.
     *
     * Defaults to null - no information set set
     *
     * @var mixed
     */
    public $info = null;

    /**
     * Custom identifier set.
     *
     * Defaults to null - no identifier set.
     *
     * @var null|string
     */
    public $identifier = null;

    /**
     * @inheritdoc
     */
    public function allowEmpty(bool $allowEmpty = true)
    {
        $this->allowEmpty = $allowEmpty;
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
    public function validate($value, $parentValue = null): array
    {
        if ($this->allowEmpty && $value === $this->emptyValue) {
            return [];
        }

        if (!$this->allowEmpty && $value === $this->emptyValue) {
            return [['type' => self::ERROR_VALUE_IS_EMPTY]];
        }

        return $this->doValidate($value, $parentValue);
    }

    /**
     * @inheritdoc
     */
    public function identifier(string $identifier)
    {
        $this->identifier = $identifier;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }


    /**
     * @inheritdoc
     */
    public function definition(): array
    {
        return Value::merge([
            'type' => $this->name(),
            'identifier' => $this->identifier,
            'info' => $this->info,
            'example' => $this->example,
            'allowEmpty' =>$this->allowEmpty,
            'emptyValue' => $this->emptyValue
        ], $this->fieldDefinition());
    }

    /**
     * @inheritdoc
     */
    public function info(string $info)
    {
        $this->info = $info;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function example($example)
    {
        $this->example = $example;
        return $this;
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
     * If a field if not required and has an empty value. This validation is not executed.
     *
     * @param mixed $value Value to be validated.
     * @param mixed $parentValue Parent value this field and value is in if applicable.
     * @return array List of errors if not valid or an empty array if it is valid.
     */
    protected abstract function doValidate($value, $parentValue = null): array;
}