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
 * Class CastOp
 * @package ArekX\RestFn\Parser\Ops
 *
 * Represents an operation to convert one type to another
 */
class CastOp implements Operation
{
    /**
     * @inheritDoc
     */
    public static function name(): string
    {
        return 'cast';
    }

    /**
     * @inheritDoc
     */
    public function validate(Evaluator $evaluator, $value)
    {
        if (count($value) !== 3) {
            return [
                'min_parameters' => 3,
                'max_parameters' => 3,
            ];
        }

        $result = $this->validateTypeValue($evaluator, $value[1]);

        if ($result !== null) {
            return $result;
        }

        $from = $evaluator->validate($value[2]);

        if ($from !== null) {
            return ['invalid_value_expression' => $from];
        }

        return null;
    }

    protected function validateTypeValue(Evaluator $evaluator, $typeValue)
    {
        if (is_array($typeValue)) {
            $byResult = $evaluator->validate($typeValue);

            if ($byResult !== null) {
                return [
                    'invalid_type_expression' => $byResult
                ];
            }
        } else if ($typeValue !== 'bool' && $typeValue !== 'int' && $typeValue !== 'float' && $typeValue !== 'string') {
            return [
                'invalid_type_value' => $typeValue
            ];
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function evaluate(Evaluator $evaluator, $value)
    {
        $cast = is_string($value[1]) ? $value[1] : $evaluator->evaluate($value[1]);
        $from = $evaluator->evaluate($value[2]);

        switch ($cast) {
            case 'bool':
                return (bool)$from;
            case 'int':
                return (int)$from;
            case 'string':
                return (string)$from;
            case 'float':
                return (float)$from;
        }

        throw new \Exception('Invalid cast name:' . $cast);
    }
}