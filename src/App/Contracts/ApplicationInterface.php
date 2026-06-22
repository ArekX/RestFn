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

namespace ArekX\RestFn\App\Contracts;

/**
 * Interface Application
 * @package ArekX\RestFn\App\Contracts
 *
 * An application bootstraps the framework for one request: it defines the
 * default wiring, lets the caller override it through configuration, and runs
 * the request to a result.
 */
interface ApplicationInterface
{
    /**
     * Runs the application for the current request and returns the result.
     *
     * The application is resolved from a configured container, so it receives
     * its dependencies through injection rather than building them itself.
     *
     * @return mixed
     */
    public function run(): mixed;
}
