<?php

declare(strict_types=1);


/**
 * Copyright 2026 Aleksandar Panic
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

use ArekX\RestFn\DI\Contracts\SharedInstanceInterface;
use ArekX\RestFn\Parser\Context;
use ArekX\RestFn\Parser\Contracts\EvaluatorInterface;
use ArekX\RestFn\Parser\Contracts\OperationInterface;
use ArekX\RestFn\Parser\Exceptions\InvalidEvaluation;

/**
 * Class CastOp
 * @package ArekX\RestFn\Parser\Ops
 *
 * Represents an operation to convert one type to another
 */
class CastOp implements OperationInterface, SharedInstanceInterface
{
    public function __construct(
        protected EvaluatorInterface $evaluator,
    ) {}

    /**
     * @inheritDoc
     */
    #[\Override]
    public static function name(): string
    {
        return 'cast';
    }

    /**
     * @inheritDoc
     */
    #[\Override]
    public function validate(array $value, Context $context): ?array
    {
        if (count($value) !== 3) {
            return [
                'min_parameters' => 3,
                'max_parameters' => 3,
            ];
        }

        $result = $this->validateTypeValue($value[1], $context);

        if ($result !== null) {
            return $result;
        }

        $from = $this->evaluator->validate($value[2], $context);

        if ($from !== null) {
            return ['invalid_value_expression' => $from];
        }

        return null;
    }

    protected function validateTypeValue(mixed $typeValue, Context $context): ?array
    {
        if (is_array($typeValue)) {
            $byResult = $this->evaluator->validate($typeValue, $context);

            if ($byResult !== null) {
                return [
                    'invalid_type_expression' => $byResult,
                ];
            }
        } elseif ($typeValue !== 'bool' && $typeValue !== 'int' && $typeValue !== 'float' && $typeValue !== 'string') {
            return [
                'invalid_type_value' => $typeValue,
            ];
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    #[\Override]
    public function evaluate(array $value, Context $context): mixed
    {
        $cast = $this->evaluator->resolve($value[1], $context);
        $from = $this->evaluator->evaluate($value[2], $context);

        switch ($cast) {
            case 'bool':
                return (bool) $from;
            case 'int':
                return (int) $from;
            case 'string':
                return (string) $from;
            case 'float':
                return (float) $from;
        }

        throw new InvalidEvaluation($this, "Invalid cast type: {$cast}.");
    }
}
