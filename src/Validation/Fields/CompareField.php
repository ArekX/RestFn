<?php
/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

namespace ArekX\JsonQL\Validation\Fields;

use ArekX\JsonQL\Validation\BaseField;

/**
 * Class CompareField Field representing a comparison type.
 * @package ArekX\JsonQL\Validation\Fields
 */
class CompareField extends BaseField
{
    const ERROR_COMPARE_VALUE_FAILED = 'compare_value_failed';
    const ERROR_COMPARE_FIELD_FAILED = 'compare_field_failed';

    /**
     * Operator which is used for comparison.
     *
     * If an operator is not set, this compare field will always pass.
     *
     * @var string
     */
    public $operator;

    /**
     * Field name used for comparison.
     *
     * @var string|null
     */
    public $fieldName;

    /**
     * Value used for comparison. If field is not set, value will be used.
     *
     * @var mixed|null
     */
    public $value;

    /**
     * Sets comparison with field.
     *
     * If a field is set, value will not be used.
     *
     * @param string $operator Operator to be set for comparison.
     * @param string $fieldName Field name used for comparison
     * @return $this
     */
    public function withField(string $operator, string $fieldName)
    {
        $this->operator = $operator;
        $this->fieldName = $fieldName;
        $this->value = null;
        return $this;
    }

    /**
     * Sets comparison with value.
     *
     * If a value is set, field will not be used.
     *
     * @param string $operator Operator to be set for comparison
     * @param mixed $value Value to which it will be compared.
     * @return $this
     */
    public function withValue(string $operator, $value)
    {
        $this->operator = $operator;
        $this->fieldName = null;
        $this->value = $value;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function name(): string
    {
        return 'compare';
    }

    /**
     * @inheritdoc
     */
    protected function fieldDefinition(): array
    {
        return [
            'operator' => $this->operator,
            'withField' => $this->fieldName,
            'withValue' => $this->value
        ];
    }

    /**
     * @inheritdoc
     */
    protected function doValidate($value, $parentValue = null): array
    {
        if ($this->operator === null) {
            return [];
        }

        if ($this->fieldName !== null) {
            $vsValue = is_array($parentValue) ? $parentValue[$this->fieldName] : null;

            if (!$this->compareValue($value, $vsValue)) {
                return [
                    self::ERROR_COMPARE_FIELD_FAILED => [
                        'withField' => $this->fieldName,
                        'operator' => $this->operator]
                ];
            }

        } else if (!$this->compareValue($value, $this->value)) {
            return [
                self::ERROR_COMPARE_VALUE_FAILED => [
                    'withValue' => $this->value,
                    'operator' => $this->operator
                ]
            ];
        }

        return [];
    }

    /**
     * Compares two values using an operator.
     *
     * @param mixed $value First value to be checked
     * @param mixed $vsValue Second value to be checked
     * @return bool Returns true if values satisfy the operator or false if they dont.
     */
    protected function compareValue($value, $vsValue)
    {
        $inverted = false;
        $result = false;
        $max = strlen($this->operator);

        for ($i = 0; $i < $max; $i++) {
            $op = $this->operator[$i];

            if ($op === '!') {
                $inverted = true;
                continue;
            }

            if ($op === '=') {
                $result = $result || $value === $vsValue;
            } else if ($op === '<') {
                $result = $result || $value < $vsValue;
            } else if ($op === '>') {
                $result = $result || $value > $vsValue;
            }
        }

        return $inverted !== $result;
    }
}