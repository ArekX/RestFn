<?php

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

use ArekX\RestFn\Parser\Contracts\Evaluator;
use ArekX\RestFn\Parser\Contracts\Operation;

/**
 * Class ObjectOp
 * @package ArekX\RestFn\Parser\Ops
 *
 * Represents Object operation
 */
class ObjectOp implements Operation
{
    /**
     * @inheritDoc
     */
    #[\Override]
    public static function name(): string
    {
        return 'object';
    }

    /**
     * @inheritDoc
     */
    #[\Override]
    public function validate(Evaluator $evaluator, $value)
    {
        if (count($value) !== 2) {
            return [
                'min_parameters' => 2,
                'max_parameters' => 2,
            ];
        }

        $errors = [];

        foreach ($value[1] as $key => $expression) {
            $result = $evaluator->validate($expression);
            if ($result) {
                $errors[$key] = $result;
            }
        }

        return !empty($errors) ? ['invalid_object_expression' => $errors] : null;
    }

    /**
     * @inheritDoc
     */
    #[\Override]
    public function evaluate(Evaluator $evaluator, $value)
    {
        $result = [];

        foreach ($value[1] as $key => $expression) {
            $result[$key] = $evaluator->evaluate($expression);
        }

        return $result;
    }
}
