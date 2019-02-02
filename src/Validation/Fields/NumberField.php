<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Validation\Fields;

use ArekX\JsonQL\Validation\BaseField;
use ArekX\JsonQL\Validation\FieldInterface;

/**
 * Class AnyOfField
 * @package ArekX\JsonQL\Validation\Fields
 *
 * Field representing a number type.
 *
 */
class NumberField extends BaseField
{
    const ERROR_NOT_A_NUMBER = 'not_a_number';
    const ERROR_NOT_AN_INT = 'not_an_int';
    const ERROR_LESS_THAN_MIN = 'less_than_min';
    const ERROR_GREATER_THAN_MAX = 'greater_than_max';

    /**
     * Whether or not to validate only integer numbers.
     *
     * @var bool
     */
    public $integerOnly = false;

    /**
     * Minimum value set.
     *
     * Defaults to null - means no value is set.
     *
     * @var null
     */
    public $min = null;

    /**
     * Maximum value set.
     *
     * Defaults to null - means no value is set.
     *
     * @var null
     */
    public $max = null;

    /**
     * Sets whether or not field must be integer only.
     *
     * @param bool $integerOnly
     * @return static
     */
    public function integerOnly(bool $integerOnly = true)
    {
        $this->integerOnly = $integerOnly;
        return $this;
    }

    /**
     * Sets minimum value to validate against.
     *
     * @param $minimum
     * @return static
     */
    public function min(int $minimum)
    {
        $this->min = $minimum;

        if ($this->max !== null && $this->min > $this->max) {
            $this->max = $this->min;
        }

        return $this;
    }

    /**
     * Sets maximum value to validate against.
     *
     * @param $maximum
     * @return static
     */
    public function max(int $maximum)
    {
        $this->max = $maximum;

        if ($this->min !== null && $this->min > $this->max) {
            $this->min = $this->max;
        }

        return $this;
    }

    /**
     * Returns name of this field.
     *
     * @return string
     */
    public function name(): string
    {
        return 'number';
    }

    /**
     * Returns field definition.
     *
     * @return array
     */
    protected function fieldDefinition(): array
    {
        return [
            'integerOnly' => $this->integerOnly,
            'minimum' => $this->min,
            'maximum' => $this->max
        ];
    }

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
    protected function doValidate(string $field, $value, $parentValue = null): array
    {
        if ($this->integerOnly && !is_int($value)) {
            return [['type' => self::ERROR_NOT_AN_INT]];
        }

        if (!is_int($value) && !is_double($value)) {
            return [['type' => self::ERROR_NOT_A_NUMBER]];
        }

        if ($this->min !== null && $value < $this->min) {
            return [['type' => self::ERROR_LESS_THAN_MIN, 'min' => $this->min]];
        }

        if ($this->max !== null && $value > $this->max) {
            return [['type' => self::ERROR_GREATER_THAN_MAX, 'max' => $this->max]];
        }

        return [];
    }
}