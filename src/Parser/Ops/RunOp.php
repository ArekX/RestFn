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

use ArekX\RestFn\DI\Container;
use ArekX\RestFn\DI\Contracts\InjectableInterface;
use ArekX\RestFn\Helper\Value;
use ArekX\RestFn\Parser\Contracts\ActionInterface;
use ArekX\RestFn\Parser\Contracts\EvaluatorInterface;
use ArekX\RestFn\Parser\Contracts\OperationInterface;

/**
 * Class RunOp
 * @package ArekX\RestFn\Parser\Ops
 *
 * Represents Run operation
 */
class RunOp implements OperationInterface, InjectableInterface
{
    /**
     * Injected injector used to make actions
     *
     * @var Container
     */
    public Container $injector;

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
    public function validate(EvaluatorInterface $evaluator, array $value)
    {
        if (count($value) !== 3) {
            return [
                'min_parameters' => 3,
                'max_parameters' => 3,
            ];
        }

        $nameResult = $this->validateActionNameValue($evaluator, $value[1]);

        if ($nameResult !== null) {
            return $nameResult;
        }

        $dataResult = $this->validateDataValue($evaluator, $value[2]);

        if ($dataResult !== null) {
            return $dataResult;
        }

        return null;
    }

    protected function validateActionNameValue(EvaluatorInterface $evaluator, $actionValue)
    {
        if (is_array($actionValue)) {
            $byResult = $evaluator->validate($actionValue);

            if ($byResult !== null) {
                return [
                    'invalid_action_expression' => $byResult,
                ];
            }
        } else if (!is_string($actionValue)) {
            return [
                'invalid_action_value' => $actionValue,
            ];
        }

        return null;
    }

    protected function validateDataValue(EvaluatorInterface $evaluator, $actionValue)
    {
        if (is_array($actionValue)) {
            $byResult = $evaluator->validate($actionValue);

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
    public function evaluate(EvaluatorInterface $evaluator, array $value)
    {
        $actionName = is_string($value[1]) ? $value[1] : $evaluator->evaluate($value[1]);
        $data = is_array($value[2]) ? $evaluator->evaluate($value[2]) : $value[2];

        $actionClass = Value::get($actionName, $evaluator->getContext('actions'));

        if (empty($actionClass)) {
            throw new \Exception('Invalid action: ' . $actionName);
        }

        /** @var ActionInterface $action */
        $action = $this->injector->make($actionClass);

        return $action->run($data);
    }
}
