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

/**
 * Class VarOp
 * @package ArekX\RestFn\Parser\Ops
 *
 * Represents setting or getting a variable operation
 */
class VarOp implements OperationInterface, SharedInstanceInterface
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
        return 'var';
    }

    /**
     * @inheritDoc
     */
    #[\Override]
    public function validate(array $value, Context $context): ?array
    {
        $max = count($value);

        if ($max < 2 || $max > 3) {
            return [
                'min_parameters' => 2,
                'max_parameters' => 3,
            ];
        }

        if (is_array($value[1])) {
            $result = $this->evaluator->validate($value[1], $context);

            if ($result !== null) {
                return [
                    'invalid_name_expression' => $result,
                ];
            }
        } elseif (!is_string($value[1])) {
            return [
                'invalid_name_value' => $value[1],
            ];
        }

        if ($max === 3) {
            $result = $this->evaluator->validate($value[2], $context);

            if ($result !== null) {
                return [
                    'invalid_value_expression' => $result,
                ];
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
        $max = count($value);

        $getter = $this->evaluator->resolve($value[1], $context);

        if ($max === 2) {
            return $context->getVariable($getter);
        }

        $setValue = $this->evaluator->evaluate($value[2], $context);
        $context->setVariable($getter, $setValue);
        return $setValue;
    }
}
