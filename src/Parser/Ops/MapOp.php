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

use ArekX\RestFn\Helper\Value;
use ArekX\RestFn\Parser\Contracts\EvaluatorInterface;
use ArekX\RestFn\Parser\Contracts\OperationInterface;

/**
 * Class MapOp
 * @package ArekX\RestFn\Parser\Ops
 *
 * Represents Map operation
 */
class MapOp implements OperationInterface
{
    /**
     * @inheritDoc
     */
    #[\Override]
    public static function name(): string
    {
        return 'map';
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

        if (!is_string($value[1]) && !is_array($value[1])) {
            return [
                'invalid_from_value' => $value[1],
            ];
        }

        if (is_array($value[1])) {
            $fromResult = $evaluator->validate($value[1]);
            if ($fromResult) {
                return [
                    'invalid_from_expression' => $fromResult,
                ];
            }
        }

        if (!is_string($value[2]) && !is_array($value[2])) {
            return [
                'invalid_to_value' => $value[2],
            ];
        }

        if (is_array($value[2])) {
            $toResult = $evaluator->validate($value[2]);
            if ($toResult) {
                return [
                    'invalid_to_expression' => $toResult,
                ];
            }
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    #[\Override]
    public function evaluate(EvaluatorInterface $evaluator, array $value)
    {
        $from = is_string($value[1]) ? $value[1] : $evaluator->evaluate($value[1]);
        $to = is_string($value[2]) ? $value[2] : $evaluator->evaluate($value[2]);

        $result = $evaluator->evaluate($value[3]);

        $mapped = [];

        foreach ($result as $item) {
            $key = Value::get($from, $item);
            $result = Value::get($to, $item);

            if (!is_string($key)) {
                throw new \Exception('Invalid key value.');
            }

            $mapped[$key] = $result;
        }

        return $mapped;
    }
}
