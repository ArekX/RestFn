<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Validation\Rules;

class StringRule extends BaseRule
{
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
            $errors[] = 'Value is not a string.';
            return $errors;
        }

        $valueLength  = mb_strlen($value);

        if ($this->minLength !== null && $this->maxLength !== null && $this->minLength === $this->maxLength) {
            if ($valueLength !== $this->minLength) {
                $errors[] = 'Value must have exact length of ' . $this->minLength . ' characters.';
            }
        }

        if ($this->minLength !== null && $valueLength < $this->minLength) {
            $errors[] = 'Value is less than ' . $this->minLength . ' characters.';
        }

        if ($this->maxLength !== null && $valueLength > $this->maxLength) {
            $errors[] = 'Value is larger than ' . $this->maxLength . ' characters.';
        }

        if ($this->mustMatch !== null && !preg_match($this->mustMatch, $value)) {
            $errors[] = 'Value must match regex: ' . $this->mustMatch;
        }

        return $errors;
    }
}