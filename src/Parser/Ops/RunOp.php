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
use ArekX\RestFn\DI\Container;
use ArekX\RestFn\Helper\Value;
use ArekX\RestFn\Parser\Context;
use ArekX\RestFn\Parser\Contracts\ActionInterface;
use ArekX\RestFn\Parser\Contracts\EvaluatorInterface;
use ArekX\RestFn\Parser\Contracts\OperationInterface;
use ArekX\RestFn\Parser\Exceptions\InvalidEvaluation;

/**
 * Class RunOp
 * @package ArekX\RestFn\Parser\Ops
 *
 * Represents Run operation
 */
class RunOp implements OperationInterface
{
    public function __construct(
        public EvaluatorInterface $evaluator,
        public Container $container,
        #[Config('actions', default: [])] public array $actions = [],
    ) {}

    /**
     * @inheritDoc
     */
    #[\Override]
    public static function name(): string
    {
        return 'run';
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

        $nameResult = $this->validateActionNameValue($value[1], $context);

        if ($nameResult !== null) {
            return $nameResult;
        }

        $dataResult = $this->validateDataValue($value[2], $context);

        if ($dataResult !== null) {
            return $dataResult;
        }

        return null;
    }

    protected function validateActionNameValue(mixed $actionValue, Context $context): ?array
    {
        if (is_array($actionValue)) {
            $byResult = $this->evaluator->validate($actionValue, $context);

            if ($byResult !== null) {
                return [
                    'invalid_action_expression' => $byResult,
                ];
            }
        } elseif (!is_string($actionValue)) {
            return [
                'invalid_action_value' => $actionValue,
            ];
        }

        return null;
    }

    protected function validateDataValue(mixed $actionValue, Context $context): ?array
    {
        if (is_array($actionValue)) {
            $byResult = $this->evaluator->validate($actionValue, $context);

            if ($byResult !== null) {
                return [
                    'invalid_data_expression' => $byResult,
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
        $actionName = is_string($value[1]) ? $value[1] : $this->evaluator->evaluate($value[1], $context);
        $data = is_array($value[2]) ? $this->evaluator->evaluate($value[2], $context) : $value[2];

        $actionClass = Value::get($actionName, $this->actions);

        if (empty($actionClass)) {
            throw new InvalidEvaluation($this, "Invalid action: {$actionName}.");
        }

        /** @var ActionInterface $action */
        $action = $this->container->make($actionClass);

        return $action->run($data);
    }
}
