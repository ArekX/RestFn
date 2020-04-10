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

namespace ArekX\RestFn\DI\Contracts;

use ArekX\RestFn\DI\Injector;

/**
 * Interface Configurable
 * @package ArekX\RestFn\DI\Contracts
 *
 * Class which implement this interface will receive config from Injector::getConfig()
 *
 * @see Injector::getConfig()
 */
interface Configurable
{
    /**
     * Configurable constructor.
     *
     * @param array $config Constructor config from Injector::getConfig() for this resolved class.
     * @param array $constructorArgs Constructor arguments passed to Injector::make()
     * @see Injector::make()
     */
    public function __construct(array $config, array $constructorArgs);
}