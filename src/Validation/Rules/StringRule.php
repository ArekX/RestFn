<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Validation\Rules;

class StringRule extends BaseRule
{
    const NOT_EXACT_LENGTH = 'not_exact_length';
    const NOT_A_STRING = 'not_a_string';
    const BELOW_MINIMUM = 'below_minimum';
    const OVER_MAXIMUM = 'over_maximum';
    const DOES_NOT_MATCH = 'does_not_match';

    protected $minLength = null;
    protected $maxLength = null;
    protected $canBeEmpty = true;
    protected $mustMatch = null;

    public function min($length = null): StringRule
    {
        $this->minLength = $length;
        return $this;
    }

    public function max($length = null): StringRule
    {
        $this->maxLength = $length;
        return $this;
    }

    public function exact($length = null): StringRule
    {
        $this->min($length);
        $this->max($length);
        return $this;
    }

    public function mustMatch($pattern = null): StringRule
    {
        $this->mustMatch = $pattern;
        return $this;
    }

    /**
     * @inheritdoc
     */
    protected function doValidate(string $field, $value, $data, $errors): array
    {
        if (!is_string($value)) {
            $errors[] = ['type' => self::NOT_A_STRING];
            return $errors;
        }

        $valueLength  = mb_strlen($value);

        if ($this->minLength !== null && $this->maxLength !== null && $this->minLength === $this->maxLength) {
            if ($valueLength !== $this->minLength) {
                $errors[] = ['type' => self::NOT_EXACT_LENGTH, 'data' => ['length' => $this->minLength]];
            }
        }

        if ($this->minLength !== null && $valueLength < $this->minLength) {
            $errors[] = ['type' => self::BELOW_MINIMUM, 'data' => ['length' => $this->minLength]];
        }

        if ($this->maxLength !== null && $valueLength > $this->maxLength) {
            $errors[] = ['type' => self::OVER_MAXIMUM, 'data' => ['length' => $this->maxLength]];
        }

        if ($this->mustMatch !== null && !preg_match($this->mustMatch, $value)) {
            $errors[] = ['type' => self::DOES_NOT_MATCH, 'data' => ['match' => $this->mustMatch]];
        }

        return $errors;
    }
}