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

namespace ArekX\RestFn\Parser\Contracts;


use ArekX\RestFn\Parser\Data\ListRequest;
use ArekX\RestFn\Parser\Data\ListResult;

/**
 * Interface ListAction
 * @package ArekX\RestFn\Parser\Contracts
 *
 * Represents one action to be ran in ListOp.
 */
interface ListAction
{
    /**
     * Runs list operation and returns the data.
     *
     * @return ListResult
     */
    public function run(ListRequest $request): ListResult;
}