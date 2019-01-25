<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Validation\Fields;

use ArekX\JsonQL\Helpers\Value;
use ArekX\JsonQL\Traits\Memoize;

class CompareField extends BaseField
{
    const COMPARE_FAILED = 'compare_failed';

    use Memoize;

    /** @var array */
    protected $fieldChecks = [];

    /** @var bool  */
    protected $inverted = false;

    public function not(): CompareField
    {
        $this->inverted = !$this->inverted;
        return $this;
    }

    public function lessThanOrEqualTo($fieldName): CompareField
    {
        return $this->addOperator('<=', $fieldName);
    }

    public function greaterThanOrEqualTo($fieldName): CompareField
    {
        return $this->addOperator('>=', $fieldName);
    }

    public function lessThan($fieldName): CompareField
    {
        return $this->addOperator('<', $fieldName);
    }

    public function greaterThan($fieldName): CompareField
    {
        return $this->addOperator('>', $fieldName);
    }

    public function equalTo($fieldName): CompareField
    {
        return $this->addOperator('=', $fieldName);
    }

    public function sameAs($fieldName): CompareField
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
        return static::staticMemoize(static::class . __METHOD__, function () {
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

    /**
     * @inheritdoc
     */
    protected function getName(): string
    {
        return 'compare';
    }

    /**
     * @inheritdoc
     */
    protected function getFieldDefinition(): array
    {
        return [
            'checks' => $this->fieldChecks
        ];
    }

    /**
     * @inheritdoc
     */
    public function clone()
    {
        $instance = new static();
        $this->setupClone($instance);
        $instance->fieldChecks = $this->fieldChecks;
        $instance->inverted = $this->inverted;
        return $instance;
    }
}