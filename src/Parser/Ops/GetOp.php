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
use ArekX\RestFn\Parser\Context;
use ArekX\RestFn\Parser\Contracts\EvaluatorInterface;
use ArekX\RestFn\Parser\Contracts\OperationInterface;

/**
 * Class GetOp
 * @package ArekX\RestFn\Parser\Ops
 *
 * Represents one get operation
 *
 */
class GetOp implements OperationInterface
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
        return 'get';
    }

    /**
     * @inheritDoc
     */
    #[\Override]
    public function validate(array $value, Context $context): ?array
    {
        if (count($value) < 3) {
            return ['min_parameters' => 3];
        }

        if (!is_string($value[1]) && !is_array($value[1])) {
            return ['invalid_getter_value' => $value[1]];
        } elseif (is_array($value[1])) {
            $paramResult = $this->evaluator->validate($value[1], $context);
            if ($paramResult !== null) {
                return ['invalid_getter_expression' => $paramResult];
            }
        }

        $valueResult = $this->evaluator->validate($value[2], $context);
        if ($valueResult !== null) {
            return ['invalid_value_expression' => $valueResult];
        }

        $defaultResult = $value[3] ?? null;
        if ($defaultResult) {
            $defaultResult = $this->evaluator->validate($defaultResult, $context);
            if ($defaultResult !== null) {
                return ['invalid_default_expression' => $defaultResult];
            }
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

        $getter = $this->evaluator->resolve($value[1], $context);

        $gotResult = Value::get($getter, $result, NAN);

        if (is_float($gotResult) && is_nan($gotResult)) {
            $default = $value[3] ?? null;
            return is_array($default) ? $this->evaluator->evaluate($default, $context) : null;
        }

        return $gotResult;
    }
}
