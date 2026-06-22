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

use ArekX\RestFn\DI\Attributes\Config;
use ArekX\RestFn\Parser\Context;
use ArekX\RestFn\Parser\Contracts\EvaluatorInterface;
use ArekX\RestFn\Parser\Contracts\OperationInterface;

/**
 * Class SequenceOp
 * @package ArekX\RestFn\Parser\Ops
 *
 * Represents operation which runs one sequence and returns last result
 */
class SequenceOp implements OperationInterface
{
    /**
     * Default maximum number of operations a sequence may contain when the
     * 'maxSequenceOperations' option is not configured.
     */
    public const DEFAULT_MAX_OPERATIONS = 64;

    public function __construct(
        public EvaluatorInterface $evaluator,
        #[Config('limits.maxSequenceOperations', default: self::DEFAULT_MAX_OPERATIONS)] public int $maxOperations = self::DEFAULT_MAX_OPERATIONS,
    ) {}

    /**
     * @inheritDoc
     */
    #[\Override]
    public static function name(): string
    {
        return 'sequence';
    }

    /**
     * @inheritDoc
     */
    #[\Override]
    public function validate(array $value, Context $context): ?array
    {
        $max = count($value);

        $operationCount = $max - 1;

        if ($operationCount > $this->maxOperations) {
            return ['max_operations' => $this->maxOperations];
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
        $max = count($value);
        $lastResult = null;

        for ($i = 1; $i < $max; $i++) {
            $lastResult = $this->evaluator->evaluate($value[$i], $context);
        }

        return $lastResult;
    }
}
