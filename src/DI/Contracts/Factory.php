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
 * Interface Factory
 * @package ArekX\RestFn\DI\Contracts
 *
 * Classes which implement Factory will have create() called so that they
 * can handle resolution on what will be created.
 *
 * Classes will not be auto-wired unless instantiated through call to Injector::make()
 *
 * @see Injector::make()
 */
interface Factory
{
    /**
     * Resolve class creation
     *
     * @param Injector $injector Injector which called this function.
     * @param array $config Configuration for this class name set by Injector::configure()
     * @param string $class Class passed to Injector::createThroughFactory()
     * @param array $constructorArgs Constructor arguments passed to Injector::createThroughFactory()
     * @see Injector::configure() Which will result in Configuration passed to this function.
     * @see Injector::createThroughFactory() Which will end up calling this function
     * @return mixed Resolved instance of the class.
     */
    public static function create(Injector $injector, array $config, string $class, array $constructorArgs);
}