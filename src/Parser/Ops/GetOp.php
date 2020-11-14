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
 * Class GetOp
 * @package ArekX\RestFn\Parser\Ops
 *
 * Represents one get operation
 *
 */
class GetOp implements Operation
{
    /**
     * @inheritDoc
     */
    public static function name(): string
    {
        return 'get';
    }

    /**
     * @inheritDoc
     */
    public function validate(Evaluator $evaluator, $value)
    {
        if (count($value) < 3) {
            return ['min_parameters' => 2];
        }

        if (!is_string($value[1]) && !is_array($value[1])) {
            return ['invalid_getter_value' => $value[1]];
        } elseif (is_array($value[1])) {
            $paramResult = $evaluator->validate($value[1]);
            if ($paramResult !== null) {
                return ['invalid_getter_expression' => $paramResult];
            }
        }

        $valueResult = $evaluator->validate($value[2]);
        if ($valueResult !== null) {
            return ['invalid_value_expression' => $valueResult];
        }

        $defaultResult = $value[3] ?? null;
        if ($defaultResult) {
            $defaultResult = $evaluator->validate($defaultResult);
            if ($defaultResult !== null) {
                return ['invalid_default_expression' => $defaultResult];
            }
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function evaluate(Evaluator $evaluator, $value)
    {
        $result = $evaluator->evaluate($value[2]);

        $getter = $value[1];

        if (is_array($getter)) {
            $getter = $evaluator->evaluate($getter);
        }

        if (array_key_exists($getter, $result)) {
            return $result[$getter];
        }

        $parts = explode('.', $getter);
        $walker = $result;

        foreach ($parts as $key) {
            if (!is_array($walker) || !array_key_exists($key, $walker)) {
                $default = $value[3] ?? null;
                if (is_array($default)) {
                    return $evaluator->evaluate($value[3]);
                }

                return null;
            }

            $walker = $walker[$key];
        }

        return $walker;
    }
}