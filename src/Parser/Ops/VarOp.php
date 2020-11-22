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


use ArekX\RestFn\Helper\Value;
use ArekX\RestFn\Parser\Contracts\Evaluator;
use ArekX\RestFn\Parser\Contracts\Operation;

/**
 * Class VarOp
 * @package ArekX\RestFn\Parser\Ops
 *
 * Represents setting or getting a variable operation
 */
class VarOp implements Operation
{
    /**
     * @inheritDoc
     */
    public static function name(): string
    {
        return 'var';
    }

    /**
     * @inheritDoc
     */
    public function validate(Evaluator $evaluator, $value)
    {
        $max = count($value);

        if ($max < 2 || $max > 3) {
            return [
                'min_parameters' => 2,
                'max_parameters' => 3
            ];
        }

        if (is_array($value[1])) {
            $result = $evaluator->validate($value[1]);

            if ($result !== null) {
                return [
                    'invalid_name_expression' => $result
                ];
            }
        } else if (!is_string($value[1])) {
            return [
                'invalid_name_value' => $value[1]
            ];
        }

        if ($max === 3) {
            $result = $evaluator->validate($value[2]);

            if ($result !== null) {
                return [
                    'invalid_value_expression' => $result
                ];
            }
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function evaluate(Evaluator $evaluator, $value)
    {
        $max = count($value);

        $getter = is_string($value[1]) ? $value[1] : $evaluator->evaluate($value[1]);

        if ($max === 2) {
            return Value::get($getter, $evaluator->getContext("variables"));
        }

        $setValue = $evaluator->evaluate($value[2]);

        $variables = $evaluator->getContext("variables") ?: [];
        $variables[$getter] = $setValue;
        $evaluator->setContext("variables", $variables);

        return $setValue;
    }
}