<?php
/**
 * Copyright 2020 Aleksandar Panic
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


use ArekX\RestFn\DI\Contracts\Injectable;
use ArekX\RestFn\DI\Injector;
use ArekX\RestFn\Helper\Value;
use ArekX\RestFn\Parser\Contracts\Evaluator;
use ArekX\RestFn\Parser\Contracts\ListAction;
use ArekX\RestFn\Parser\Contracts\Operation;
use ArekX\RestFn\Parser\Data\ListRequest;

/**
 * Class ListOp
 * @package ArekX\RestFn\Parser\Ops
 *
 * Represents List operation
 */
class ListOp implements Operation, Injectable
{
    /**
     * Injected injector used to make actions
     *
     * @var Injector
     */
    public Injector $injector;

    /**
     * @inheritDoc
     */
    public static function name(): string
    {
        return 'run';
    }

    /**
     * @inheritDoc
     */
    public function validate(Evaluator $evaluator, $value)
    {
        if (count($value) !== 3) {
            return [
                'min_parameters' => 3,
                'max_parameters' => 3
            ];
        }

        $nameResult = $this->validateActionNameValue($evaluator, $value[1]);

        if ($nameResult !== null) {
            return $nameResult;
        }

        $dataResult = $evaluator->validate($value[2]);

        if ($dataResult !== null) {
            return [
                'invalid_data_expression' => $dataResult
            ];
        }

        return null;
    }

    protected function validateActionNameValue(Evaluator $evaluator, $actionValue)
    {
        if (is_array($actionValue)) {
            $byResult = $evaluator->validate($actionValue);

            if ($byResult !== null) {
                return [
                    'invalid_action_expression' => $byResult
                ];
            }
        } else if (!is_string($actionValue)) {
            return [
                'invalid_action_value' => $actionValue
            ];
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function evaluate(Evaluator $evaluator, $value)
    {
        $actionName = is_string($value[1]) ? $value[1] : $evaluator->evaluate($value[1]);
        $data = $evaluator->evaluate($value[2]);

        $request = new ListRequest($data);

        if (!$request->hasProperties()) {
            throw new \Exception('Properties must be specified.');
        }

        $actionClass = Value::get($actionName, $evaluator->getContext('listActions'));

        if (empty($actionClass)) {
            throw new \Exception('Invalid list action: ' . $actionName);
        }

        /** @var ListAction $action */
        $action = $this->injector->make($actionClass);

        $result = $action->run($request);

        return [
            'total' => $result->getTotalItems(),
            'result' => $result->getResult()
        ];
    }
}