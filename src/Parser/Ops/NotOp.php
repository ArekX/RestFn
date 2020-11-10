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
 * Class NotOp
 * @package ArekX\RestFn\Parser\Ops
 *
 * Represents NOT operation
 */
class NotOp implements Operation
{
    /**
     * @inheritDoc
     */
    public static function name(): string
    {
        return 'not';
    }

    /**
     * @inheritDoc
     */
    public function validate(Evaluator $evaluator, $value)
    {
        if (count($value) !== 2) {
            return [
                'min_parameters' => 1,
                'max_parameters' => 1
            ];
        }

        $result = $evaluator->validate($value[1]);

        if ($result !== null) {
            return ['op_error' => $result];
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function evaluate(Evaluator $evaluator, $value)
    {
        return !$evaluator->evaluate($value[1]);
    }
}