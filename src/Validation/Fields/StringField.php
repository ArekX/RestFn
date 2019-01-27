<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Validation\Fields;

class StringField extends BaseField
{
    const NOT_EXACT_LENGTH = 'not_exact_length';
    const NOT_A_STRING = 'not_a_string';
    const BELOW_MINIMUM = 'below_minimum';
    const OVER_MAXIMUM = 'over_maximum';
    const DOES_NOT_MATCH = 'does_not_match';

    /** @var null|int */
    protected $minLength = null;

    /** @var null|int */
    protected $maxLength = null;

    /** @var null|int */
    protected $mustMatch = null;

    /**
     * Set minimum length of string.
     *
     * @param null|int $length Length of a string
     * @return static
     */
    public function min($length = null): StringField
    {
        $this->minLength = $length;
        return $this;
    }

    /**
     * Set maximum length of string.
     *
     * @param null|int $length Length of a string
     * @return static
     */
    public function max($length = null): StringField
    {
        $this->maxLength = $length;
        return $this;
    }

    /**
     * Set minimum/maximum length of string.
     *
     * @param null|int $length Length of a string
     * @return static
     */
    public function exact($length = null): StringField
    {
        $this->min($length);
        $this->max($length);
        return $this;
    }

    /**
     * Set pattern match of a string.
     *
     * @param null|int $length Length of a string
     * @return static
     */
    public function mustMatch($pattern = null): StringField
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

        $valueLength = mb_strlen($value);

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

    /**
     * @inheritdoc
     */
    protected function getName(): string
    {
        return 'string';
    }

    /**
     * @inheritdoc
     */
    protected function getFieldDefinition(): array
    {
        return [
            'minLength' => $this->minLength,
            'maxLength' => $this->maxLength,
            'match' => $this->mustMatch
        ];
    }


    /**
     * @inheritdoc
     */
    public function clone()
    {
        $instance = new static();
        $this->setupClone($instance);
        $instance->minLength = $this->minLength;
        $instance->maxLength = $this->maxLength;
        $instance->mustMatch = $this->mustMatch;
        return $instance;
    }
}