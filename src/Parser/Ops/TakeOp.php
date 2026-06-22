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

use ArekX\RestFn\Parser\Context;
use ArekX\RestFn\Parser\Contracts\EvaluatorInterface;
use ArekX\RestFn\Parser\Contracts\OperationInterface;
use ArekX\RestFn\Parser\Exceptions\InvalidEvaluation;

/**
 * Class TakeOp
 * @package ArekX\RestFn\Parser\Ops
 *
 * Represents one take operation
 *
 */
class TakeOp implements OperationInterface
{
    public function __construct(
        public EvaluatorInterface $evaluator,
    ) {}

    /**
     * @inheritDoc
     */
    #[\Override]
    public static function name(): string
    {
        return 'take';
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

        if (!is_numeric($value[1]) && !is_array($value[1])) {
            return ['invalid_amount' => $value[1]];
        }

        if (is_array($value[1])) {
            $takeResult = $this->evaluator->validate($value[1], $context);
            if ($takeResult) {
                return ['invalid_amount_expression' => $takeResult];
            }
        }

        $errors = $this->evaluator->validate($value[2], $context);

        if ($errors !== null) {
            return ['value_error' => $errors];
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    #[\Override]
    public function evaluate(array $value, Context $context): mixed
    {
        $result = $this->evaluator->evaluate($value[2], $context);

        if (!is_array($result)) {
            throw new InvalidEvaluation($this, 'Expected result to be an array.');
        }

        $amount = $value[1];

        if (is_array($amount)) {
            $amount = $this->evaluator->evaluate($amount, $context);
        }

        if ($amount == 0) {
            return [];
        }

        return $amount > 0 ? array_slice($result, 0, $amount) : array_slice($result, $amount);
    }
}
