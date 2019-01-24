<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Validation\Rules;

class NumberRule extends BaseRule
{
    const NOT_A_NUMBER = 'not_a_number';
    const NOT_AN_INTEGER = 'not_an_integer';
    const BELOW_MINIMUM = 'below_minimum';
    const OVER_MAXIMUM = 'over_maximum';

    protected $min = null;
    protected $max = null;
    protected $integerOnly = null;

    public function min(?int $minimum = null): NumberRule
    {
        $this->min = $minimum;
        return $this;
    }


    public function max(?int $maximum = null): NumberRule
    {
        $this->max = $maximum;
        return $this;
    }

    public function integerOnly(?bool $value = null): NumberRule
    {
        $this->integerOnly = $value;
        return $this;
    }

    /**
     * @inheritdoc
     */
    protected function doValidate(string $field, $value, $data, $errors): array
    {
        if (!is_numeric($value)) {
            $errors[] = ['type' => self::NOT_A_NUMBER];
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
}