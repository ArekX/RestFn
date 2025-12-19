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

use ArekX\RestFn\Parser\Contracts\EvaluatorInterface;
use ArekX\RestFn\Parser\Contracts\OperationInterface;

/**
 * Class IfElseOp
 * @package ArekX\RestFn\Parser\Ops
 *
 * Represents If ELSE operation
 */
class IfElseOp implements OperationInterface
{
    /**
     * @inheritDoc
     */
    #[\Override]
    public static function name(): string
    {
        return 'ifElse';
    }

    /**
     * @inheritDoc
     */
    #[\Override]
    public function validate(EvaluatorInterface $evaluator, array $value)
    {
        if (count($value) !== 4) {
            return [
                'min_parameters' => 4,
                'max_parameters' => 4,
            ];
        }

        $ifResult = $evaluator->validate($value[1]);
        if ($ifResult) {
            return ['if_expression_invalid' => $ifResult];
        }

        $trueExpressionResult = $evaluator->validate($value[2]);
        if ($trueExpressionResult) {
            return ['true_expression_invalid' => $trueExpressionResult];
        }

        $falseExpressionResult = $evaluator->validate($value[3]);
        if ($falseExpressionResult) {
            return ['false_expression_invalid' => $falseExpressionResult];
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    #[\Override]
    public function evaluate(EvaluatorInterface $evaluator, array $value)
    {
        $result = $evaluator->evaluate($value[1]);
        return $evaluator->evaluate($value[$result ? 2 : 3]);
    }
}
