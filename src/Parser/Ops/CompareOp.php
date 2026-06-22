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

use ArekX\RestFn\Parser\Context;
use ArekX\RestFn\Parser\Contracts\EvaluatorInterface;
use ArekX\RestFn\Parser\Contracts\OperationInterface;

/**
 * Class CompareOp
 * @package ArekX\RestFn\Parser\Ops
 *
 * Represents Comparison operation
 */
class CompareOp implements OperationInterface
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
        return 'compare';
    }

    /**
     * @inheritDoc
     */
    #[\Override]
    public function validate(array $value, Context $context): ?array
    {
        if (count($value) !== 4) {
            return [
                'min_parameters' => 4,
                'max_parameters' => 4,
            ];
        }

        $leftResult = $this->evaluator->validate($value[1], $context);

        if ($leftResult !== null) {
            return ['invalid_left_expression' => $leftResult];
        }

        $rightResult = $this->evaluator->validate($value[3], $context);

        if ($rightResult !== null) {
            return ['invalid_right_expression' => $rightResult];
        }

        $operation = $value[2] ?? '';

        if (is_array($operation)) {
            $operationResult = $this->evaluator->validate($operation, $context);
            if ($operationResult) {
                return ['invalid_operation_expression' => $operationResult];
            }

            return null;
        }

        switch ($operation) {
            case '=':
                break;
            case '==':
                break;
            case '>':
                break;
            case '<':
                break;
            case '!=':
                break;
            case '!==':
                break;
            case '>=':
                break;
            case '<=':
                break;
            default:
                return ['invalid_operation' => $operation];
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    #[\Override]
    public function evaluate(array $value, Context $context): mixed
    {
        $leftResult = $this->evaluator->evaluate($value[1], $context);
        $rightResult = $this->evaluator->evaluate($value[3], $context);

        switch ($value[2]) {
            case '=':
                return $leftResult == $rightResult;
            case '==':
                return $leftResult === $rightResult;
            case '>':
                return $leftResult > $rightResult;
            case '<':
                return $leftResult < $rightResult;
            case '!=':
                return $leftResult != $rightResult;
            case '!==':
                return $leftResult !== $rightResult;
            case '>=':
                return $leftResult >= $rightResult;
            case '<=':
                return $leftResult <= $rightResult;
        }

        return false;
    }
}
