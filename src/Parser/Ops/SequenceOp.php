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
 * Class SequenceOp
 * @package ArekX\RestFn\Parser\Ops
 *
 * Represents operation which runs one sequence and returns last result
 */
class SequenceOp implements OperationInterface
{
    /**
     * @inheritDoc
     */
    #[\Override]
    public static function name(): string
    {
        return 'sequence';
    }

    /**
     * @inheritDoc
     */
    #[\Override]
    public function validate(EvaluatorInterface $evaluator, array $value)
    {
        $max = count($value);

        $errors = [];

        for ($i = 1; $i < $max; $i++) {
            $result = $evaluator->validate($value[$i]);
            if ($result) {
                $errors[$i] = $result;
            }
        }

        return !empty($errors) ? ['op_errors' => $errors] : null;
    }

    /**
     * @inheritDoc
     */
    #[\Override]
    public function evaluate(EvaluatorInterface $evaluator, array $value)
    {
        $max = count($value);
        $lastResult = null;

        for ($i = 1; $i < $max; $i++) {
            $lastResult = $evaluator->evaluate($value[$i]);
        }

        return $lastResult;
    }
}
