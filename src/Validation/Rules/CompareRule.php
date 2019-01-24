<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Validation\Rules;

use ArekX\JsonQL\Helpers\Value;
use ArekX\JsonQL\Traits\Memoize;

class CompareRule extends BaseRule
{
    const COMPARE_FAILED = 'compare_failed';

    use Memoize;

    /** @var array */
    protected $fieldChecks = [];
    protected $inverted = false;

    public function __construct(string $withField)
    {
        $this->withField = $withField;
    }

    public function not(): CompareRule
    {
        $this->inverted = !$this->inverted;
        return $this;
    }

    public function lessThanOrEqualTo($fieldName): CompareRule
    {
        return $this->addOperator('<=', $fieldName);
    }

    public function greaterThanOrEqualTo($fieldName): CompareRule
    {
        return $this->addOperator('>=', $fieldName);
    }

    public function lessThan($fieldName): CompareRule
    {
        return $this->addOperator('<', $fieldName);
    }

    public function greaterThan($fieldName): CompareRule
    {
        return $this->addOperator('>', $fieldName);
    }

    public function equalTo($fieldName): CompareRule
    {
        return $this->addOperator('=', $fieldName);
    }

    public function sameAs($fieldName): CompareRule
    {
        return $this->addOperator('==', $fieldName);
    }

    protected function addOperator($operator, $fieldName)
    {
        $this->fieldChecks[] = [$this->inverted, $operator, $fieldName];
        $this->inverted = false;
        return $this;
    }

    protected static function getOperatorMap()
    {
        return static::staticMemoize(__METHOD__, function () {
            return [
                '=' => function ($valueA, $valueB) {
                    return $valueA == $valueB;
                },
                '==' => function ($valueA, $valueB) {
                    return $valueA === $valueB;
                },
                '>' => function ($valueA, $valueB) {
                    return $valueA > $valueB;
                },
                '>=' => function ($valueA, $valueB) {
                    return $valueA >= $valueB;
                },
                '<' => function ($valueA, $valueB) {
                    return $valueA < $valueB;
                },
                '<=' => function ($valueA, $valueB) {
                    return $valueA <= $valueB;
                }
            ];
        });
    }

    /**
     * @inheritdoc
     */
    protected function doValidate(string $field, $value, $data, $errors): array
    {
        $operatorMap = static::getOperatorMap();

        foreach ($this->fieldChecks as $check) {
            [$isInverted, $operator, $vsField] = $check;
            $expectedResult = $isInverted ? false : true;
            $result = $isInverted ?
                !$operatorMap[$operator]($value, Value::get($data, $vsField)) :
                $operatorMap[$operator]($value, Value::get($data, $vsField));

            if ($result !== $expectedResult) {
                $errors[] = [
                    'type' => self::COMPARE_FAILED,
                    'data' => [
                        'vsField' => $vsField,
                        'inverted' => $isInverted,
                        'operator' => $operator
                    ]
                ];
            }
        }

        return $errors;
    }
}