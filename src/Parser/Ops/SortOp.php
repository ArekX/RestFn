<?php

declare(strict_types=1);


/**
 * Copyright 2025 Aleksandar Panic
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 **/

namespace ArekX\RestFn\Parser\Ops;

use ArekX\RestFn\Helper\Value;
use ArekX\RestFn\Parser\Contracts\EvaluatorInterface;
use ArekX\RestFn\Parser\Contracts\OperationInterface;

/**
 * Class SortOp
 * @package ArekX\RestFn\Parser\Ops
 *
 * Represents Sorting operation
 */
// @mago-ignore lint:cyclomatic-complexity
class SortOp implements OperationInterface
{
    /**
     * @inheritDoc
     */
    #[\Override]
    public static function name(): string
    {
        return 'sort';
    }

    /**
     * @inheritDoc
     */
    #[\Override]
    public function validate(EvaluatorInterface $evaluator, array $value)
    {
        $max = count($value);
        if ($max > 4 || $max < 3) {
            return [
                'min_parameters' => 3,
                'max_parameters' => 4,
            ];
        }

        if ($max === 4) {
            return $this->validateBySignature($evaluator, $value);
        }

        return $this->validateNormalSignature($evaluator, $value);
    }

    protected function validateBySignature(EvaluatorInterface $evaluator, $value)
    {
        $result = $this->validateByValue($evaluator, $value[1]);
        if ($result) {
            return $result;
        }

        $result = $this->validateDirectionValue($evaluator, $value[2]);
        if ($result) {
            return $result;
        }

        $result = $this->validateFromValue($evaluator, $value[3]);
        if ($result) {
            return $result;
        }

        return null;
    }

    protected function validateByValue(EvaluatorInterface $evaluator, $byValue)
    {
        if (is_array($byValue)) {
            $byResult = $evaluator->validate($byValue);

            if ($byResult !== null) {
                return [
                    'invalid_by_expression' => $byResult,
                ];
            }
        } elseif (!is_string($byValue) && !is_int($byValue)) {
            return [
                'invalid_by_value' => $byValue,
            ];
        }

        return null;
    }

    protected function validateDirectionValue(EvaluatorInterface $evaluator, $directionValue)
    {
        if (is_array($directionValue)) {
            $byResult = $evaluator->validate($directionValue);

            if ($byResult !== null) {
                return [
                    'invalid_direction_expression' => $byResult,
                ];
            }
        } elseif ($directionValue !== 'asc' && $directionValue !== 'desc') {
            return [
                'invalid_direction_value' => $directionValue,
            ];
        }

        return null;
    }

    protected function validateFromValue(EvaluatorInterface $evaluator, $fromValue)
    {
        $byResult = $evaluator->validate($fromValue);

        if ($byResult !== null) {
            return [
                'invalid_from_expression' => $byResult,
            ];
        }

        return null;
    }

    protected function validateNormalSignature(EvaluatorInterface $evaluator, $value)
    {
        $result = $this->validateDirectionValue($evaluator, $value[1]);
        if ($result) {
            return $result;
        }

        $result = $this->validateFromValue($evaluator, $value[2]);
        if ($result) {
            return $result;
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    #[\Override]
    public function evaluate(EvaluatorInterface $evaluator, array $value)
    {
        $max = count($value);

        if ($max === 4) {
            $byValue = is_string($value[1]) || is_int($value[1]) ? $value[1] : $evaluator->evaluate($value[1]);
            $direction = is_string($value[2]) ? $value[2] : $evaluator->evaluate($value[2]);
            $from = $evaluator->evaluate($value[3]);

            if ($direction === 'asc') {
                usort($from, static fn($a, $b) => Value::get($byValue, $a) <=> Value::get($byValue, $b));
            } else {
                usort($from, static fn($a, $b) => Value::get($byValue, $b) <=> Value::get($byValue, $a));
            }

            return $from;
        }

        $direction = is_string($value[1]) ? $value[1] : $evaluator->evaluate($value[1]);
        $from = $evaluator->evaluate($value[2]);

        if ($direction === 'asc') {
            usort($from, static fn($a, $b) => $a <=> $b);
        } else {
            usort($from, static fn($a, $b) => $b <=> $a);
        }

        return $from;
    }
}
