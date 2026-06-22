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
use ArekX\RestFn\Parser\Context;
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
    public function __construct(
        public EvaluatorInterface $evaluator,
    ) {}

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
    public function validate(array $value, Context $context): ?array
    {
        $max = count($value);
        if ($max > 4 || $max < 3) {
            return [
                'min_parameters' => 3,
                'max_parameters' => 4,
            ];
        }

        if ($max === 4) {
            return $this->validateBySignature($value, $context);
        }

        return $this->validateNormalSignature($value, $context);
    }

    protected function validateBySignature(mixed $value, Context $context): ?array
    {
        $result = $this->validateByValue($value[1], $context);
        if ($result) {
            return $result;
        }

        $result = $this->validateDirectionValue($value[2], $context);
        if ($result) {
            return $result;
        }

        $result = $this->validateFromValue($value[3], $context);
        if ($result) {
            return $result;
        }

        return null;
    }

    protected function validateByValue(mixed $byValue, Context $context): ?array
    {
        if (is_array($byValue)) {
            $byResult = $this->evaluator->validate($byValue, $context);

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

    protected function validateDirectionValue(mixed $directionValue, Context $context): ?array
    {
        if (is_array($directionValue)) {
            $byResult = $this->evaluator->validate($directionValue, $context);

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

    protected function validateFromValue(mixed $fromValue, Context $context): ?array
    {
        $byResult = $this->evaluator->validate($fromValue, $context);

        if ($byResult !== null) {
            return [
                'invalid_from_expression' => $byResult,
            ];
        }

        return null;
    }

    protected function validateNormalSignature(mixed $value, Context $context): ?array
    {
        $result = $this->validateDirectionValue($value[1], $context);
        if ($result) {
            return $result;
        }

        $result = $this->validateFromValue($value[2], $context);
        if ($result) {
            return $result;
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    #[\Override]
    public function evaluate(array $value, Context $context): mixed
    {
        $max = count($value);

        if ($max === 4) {
            $byValue = $this->evaluator->resolve($value[1], $context);
            $direction = $this->evaluator->resolve($value[2], $context);
            $from = $this->evaluator->evaluate($value[3], $context);

            if ($direction === 'asc') {
                usort($from, static fn($a, $b) => Value::get($byValue, $a) <=> Value::get($byValue, $b));
            } else {
                usort($from, static fn($a, $b) => Value::get($byValue, $b) <=> Value::get($byValue, $a));
            }

            return $from;
        }

        $direction = $this->evaluator->resolve($value[1], $context);
        $from = $this->evaluator->evaluate($value[2], $context);

        if ($direction === 'asc') {
            usort($from, static fn($a, $b) => $a <=> $b);
        } else {
            usort($from, static fn($a, $b) => $b <=> $a);
        }

        return $from;
    }
}
