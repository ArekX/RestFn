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

namespace ArekX\RestFn\Parser;

use ArekX\RestFn\DI\Attributes\Config;
use ArekX\RestFn\DI\Container;
use ArekX\RestFn\DI\Contracts\SharedInstanceInterface;
use ArekX\RestFn\Parser\Contracts\EvaluatorInterface;
use ArekX\RestFn\Parser\Contracts\OperationInterface;
use ArekX\RestFn\Parser\Exceptions\InvalidOperation;
use ArekX\RestFn\Parser\Exceptions\InvalidValueFormatException;
use ArekX\RestFn\Parser\Exceptions\MaxDepthExceededException;

/**
 * Class Parser
 * @package ArekX\RestFn\Parser
 *
 * This class represents a parser which is used to handle the requests.
 */
class Parser implements EvaluatorInterface, SharedInstanceInterface
{
    /**
     * @param Container $container Container used to create operations.
     * @param array $operations Map of operation name to operation class, from the 'ops' config value.
     * @param int $maxDepth Maximum allowed nesting depth, from the 'limits.maxDepth' config value.
     *                      Guards against stack exhaustion from deeply nested, client-supplied expressions.
     */
    public function __construct(
        protected Container $container,
        #[Config('ops', default: [])] protected array $operations = [],
        #[Config('limits.maxDepth', default: 64)] protected int $maxDepth = 64,
    ) {}

    /**
     * Performs value validation.
     *
     * If a value is valid for evaluating null is returned,
     * otherwise errors are returned in nested format.
     *
     * @param mixed $value
     * @param Context $context
     * @return array|null
     * @throws InvalidOperation
     * @throws InvalidValueFormatException
     * @throws MaxDepthExceededException
     */
    #[\Override]
    public function validate(mixed $value, Context $context): null|array
    {
        if (empty($value)) {
            return null;
        }

        $this->enterDepth($context);

        try {
            $result = $this->getOperation($value)->validate($value, $context);
        } finally {
            $context->leave();
        }

        if ($result !== null) {
            return [$this->getRuleName($value), $result];
        }

        return null;
    }

    /**
     * Increments the context depth and ensures it does not exceed the maximum.
     *
     * @param Context $context
     * @throws MaxDepthExceededException When the configured maximum depth is exceeded.
     */
    protected function enterDepth(Context $context): void
    {
        $context->enter();

        if ($context->getDepth() > $this->maxDepth) {
            $context->leave();
            throw new MaxDepthExceededException($this->maxDepth);
        }
    }

    /**
     * Returns operation based on a value
     *
     * @param $value
     * @return OperationInterface
     * @throws InvalidOperation
     * @throws InvalidValueFormatException
     */
    protected function getOperation(mixed $value): OperationInterface
    {
        if (!is_array($value)) {
            throw new InvalidValueFormatException();
        }

        $ruleName = $this->getRuleName($value);

        if (empty($this->operations[$ruleName])) {
            throw new InvalidOperation($ruleName);
        }

        $operationClass = $this->operations[$ruleName];

        return $this->container->make($operationClass);
    }

    protected function getRuleName(mixed $value): string
    {
        return $value[0] ?? '';
    }

    /**
     * Evaluates a value and returns a result.
     *
     * @param mixed $value
     * @param Context $context
     * @return array|mixed
     * @throws InvalidOperation
     * @throws InvalidValueFormatException
     * @throws MaxDepthExceededException
     */
    #[\Override]
    public function evaluate(mixed $value, Context $context): mixed
    {
        if (empty($value)) {
            return [];
        }

        $this->enterDepth($context);

        try {
            return $this->getOperation($value)->evaluate($value, $context);
        } finally {
            $context->leave();
        }
    }

    /**
     * @inheritDoc
     */
    #[\Override]
    public function resolve(mixed $value, Context $context): mixed
    {
        return is_array($value) ? $this->evaluate($value, $context) : $value;
    }
}
