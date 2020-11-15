<?php
/**
 * Copyright 2020 Aleksandar Panic
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


use ArekX\RestFn\Parser\Contracts\Evaluator;
use ArekX\RestFn\Parser\Contracts\Operation;

/**
 * Class CompareOp
 * @package ArekX\RestFn\Parser\Ops
 *
 * Represents Comparison operation
 */
class CompareOp implements Operation
{
    /**
     * @inheritDoc
     */
    public static function name(): string
    {
        return 'compare';
    }

    /**
     * @inheritDoc
     */
    public function validate(Evaluator $evaluator, $value)
    {
        if (count($value) !== 4) {
            return [
                'min_parameters' => 4,
                'max_parameters' => 4
            ];
        }

        $leftResult = $evaluator->validate($value[1]);

        if ($leftResult !== null) {
            return ['invalid_left_expression' => $leftResult];
        }

        $rightResult = $evaluator->validate($value[3]);

        if ($rightResult !== null) {
            return ['invalid_right_expression' => $rightResult];
        }

        $operation = $value[2] ?? '';

        if (is_array($operation)) {
            $operationResult = $evaluator->validate($operation);
            if ($operationResult) {
                return ['invalid_operation_expression' => $operationResult];
            }
        } else {
            switch ($operation) {
                case '=':
                    break;
                case '>':
                    break;
                case '<':
                    break;
                case '!=':
                    break;
                case '>=':
                    break;
                case '<=':
                    break;
                default:
                    return ['invalid_operation' => $operation];
            }
        }


        return null;
    }

    /**
     * @inheritDoc
     */
    public function evaluate(Evaluator $evaluator, $value)
    {
        $leftResult = $evaluator->evaluate($value[1]);
        $rightResult = $evaluator->evaluate($value[3]);

        switch ($value[2]) {
            case '=':
                return $leftResult == $rightResult;
            case '>':
                return $leftResult > $rightResult;
            case '<':
                return $leftResult < $rightResult;
            case '!=':
                return $leftResult != $rightResult;
            case '>=':
                return $leftResult >= $rightResult;
            case '<=':
                return $leftResult <= $rightResult;
        }

        return false;
    }
}