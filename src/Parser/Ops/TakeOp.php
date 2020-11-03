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
use Exception;

/**
 * Class TakeOp
 * @package ArekX\RestFn\Parser\Ops
 *
 * Represents one take operation
 *
 */
class TakeOp implements Operation
{
    /**
     * @inheritDoc
     */
    public static function name(): string
    {
        return 'take';
    }

    /**
     * @inheritDoc
     */
    public function validate(Evaluator $evaluator, $value)
    {
        if (count($value) !== 3) {
            return ['min_parameters' => 2];
        }

        if (!is_numeric($value[1])) {
            return ['invalid_amount' => $value[1]];
        }

        $errors = $evaluator->validate($value[2]);

        if ($errors !== null) {
            return ['value_error' => $errors];
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function evaluate(Evaluator $evaluator, $value)
    {
        $result = $evaluator->evaluate($value[2]);

        if (!is_array($result)) {
            throw new Exception('Result must be an array');
        }

        if ($value[1] == 0) {
            return [];
        }

        return $value[1] > 0 ? array_slice($result, 0, $value[1]) : array_slice($result, $value[1]);
    }
}