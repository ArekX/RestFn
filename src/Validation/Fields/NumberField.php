<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Validation\Fields;

use ArekX\JsonQL\Validation\FieldInterface;

class NumberField extends BaseField
{
    const NOT_A_NUMBER = 'not_a_number';
    const NOT_AN_INTEGER = 'not_an_integer';
    const BELOW_MINIMUM = 'below_minimum';
    const OVER_MAXIMUM = 'over_maximum';

    protected $min = null;
    protected $max = null;
    protected $integerOnly = null;

    /**
     * Sets minimum numeric value.
     *
     * @param int|null $minimum
     * @return static
     */
    public function min(?int $minimum = null): NumberField
    {
        $this->min = $minimum;
        return $this;
    }

    /**
     * Sets maximum numeric value.
     *
     * @param int|null $maximum
     * @return static
     */
    public function max(?int $maximum = null): NumberField
    {
        $this->max = $maximum;
        return $this;
    }

    /**
     * Sets if number must be integer only.
     *
     * @param int|null $value
     * @return static
     */
    public function integerOnly(?bool $value = null): NumberField
    {
        $this->integerOnly = $value;
        return $this;
    }

    /**
     * @inheritdoc
     */
    protected function doValidate(string $field, $value, $data, $errors): array
    {
        $typeError = $this->validateType($value);

        if ($typeError !== null) {
            $errors[] = $typeError;
            return $errors;
        }

        if ($this->integerOnly && !is_int($value)) {
            $errors[] = ['type' => self::NOT_AN_INTEGER];
            return $errors;
        }

        if ($this->min !== null && $value < $this->min) {
            $errors[] = ['type' => self::BELOW_MINIMUM, 'data' => ['value' => $this->min]];
        }

        if ($this->max !== null && $value > $this->max) {
            $errors[] = ['type' => self::OVER_MAXIMUM, 'data' => ['value' => $this->max]];
        }

        return $errors;
    }

    protected function validateType($value)
    {
        if ($this->strict && (!is_float($value) && !is_int($value))) {
            return ['type' => self::NOT_A_NUMBER];
        } elseif (!is_numeric($value)) {
            return ['type' => self::NOT_A_NUMBER];
        }

        return null;
    }


    /**
     * @inheritdoc
     */
    protected function getName(): string
    {
        return 'number';
    }

    /**
     * @inheritdoc
     */
    protected function getFieldDefinition(): array
    {
        return [
            'minimum' => $this->min,
            'maximum' => $this->max,
            'integerOnly' => $this->integerOnly
        ];
    }

    /**
     * @inheritdoc
     */
    public function clone()
    {
        $instance = new static();
        $this->setupClone($instance);
        $instance->min = $this->min;
        $instance->max = $this->max;
        $instance->integerOnly = $this->integerOnly;
        return $instance;
    }
}