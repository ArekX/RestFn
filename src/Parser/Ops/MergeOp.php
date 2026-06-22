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

/**
 * Class MergeOp
 * @package ArekX\RestFn\Parser\Ops
 *
 * Represents Join operation
 */
class MergeOp implements OperationInterface
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
        return 'merge';
    }

    /**
     * @inheritDoc
     */
    #[\Override]
    public function validate(array $value, Context $context): ?array
    {
        $max = count($value);
        if ($max < 2) {
            return ['min_parameters' => 2];
        }

        $errors = [];

        for ($i = 1; $i < $max; $i++) {
            $result = $this->evaluator->validate($value[$i], $context);
            if ($result) {
                $errors[$i] = $result;
            }
        }

        return !empty($errors) ? ['op_errors' => $errors] : null;
    }

    /**
     * @inheritDoc
     */
    #[\Override]
    public function evaluate(array $value, Context $context): mixed
    {
        $result = [];

        $max = count($value);
        for ($i = 1; $i < $max; $i++) {
            $result = array_merge($result, $this->evaluator->evaluate($value[$i], $context));
        }

        return $result;
    }
}
