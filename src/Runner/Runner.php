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

namespace ArekX\RestFn\Runner;

use ArekX\RestFn\DI\Attributes\Config;
use ArekX\RestFn\DI\Container;
use ArekX\RestFn\Parser\Context;
use ArekX\RestFn\Parser\Contracts\EvaluatorInterface;
use ArekX\RestFn\Runner\Contracts\MiddlewareInterface;
use ArekX\RestFn\Runner\Contracts\RequestParserInterface;
use ArekX\RestFn\Runner\Contracts\ResponseInterface;
use ArekX\RestFn\Runner\Exceptions\InvalidRequestException;

/**
 * Class Runner
 * @package ArekX\RestFn\Runner
 *
 * Drives a single request end to end: it parses the request, wraps handling in
 * the configured middleware, validates and evaluates the request body, and
 * returns the result.
 *
 * Typical usage:
 * ```php
 * $container = new Container([...]);
 * $result = $container->make(Runner::class)->run();
 * ```
 */
class Runner
{
    /**
     * @param EvaluatorInterface $parser Evaluator used to validate and evaluate the request.
     * @param RequestParserInterface $requestParser Turns the incoming request into a Request.
     * @param ResponseInterface $response Produces the response from the evaluated result.
     * @param Container $container Container used to resolve middleware.
     * @param array $middleware Ordered list of middleware class names, from the 'runner.middleware' config value.
     */
    public function __construct(
        protected EvaluatorInterface $parser,
        protected RequestParserInterface $requestParser,
        protected ResponseInterface $response,
        protected Container $container,
        #[Config('runner.middleware', default: [])]
        protected array $middleware = [],
    ) {}

    /**
     * Runs the current request and returns the response for the evaluated result.
     *
     * @return mixed
     * @throws InvalidRequestException When the request body is invalid.
     */
    public function run(): mixed
    {
        $context = new Context();

        $result = $this->buildPipeline()($this->readRequest(), $context);

        return $this->response->respond($result);
    }

    /**
     * Reads the request, deferring a parse failure into the pipeline.
     *
     * Parsing happens before the middleware run, so a failure here would escape
     * past the error middleware. Capturing it as the request body lets handle()
     * re-throw it inside the pipeline, where the error middleware can turn it into
     * a response like any other error.
     *
     * @return Request
     */
    protected function readRequest(): Request
    {
        try {
            return $this->requestParser->parse();
        } catch (\Throwable $error) {
            return new Request($error);
        }
    }

    /**
     * Builds the middleware pipeline around the core handler.
     *
     * Middleware are applied so that the first configured middleware is the
     * outermost: it runs first on the way in and last on the way out.
     *
     * @return callable fn(Request, Context): mixed
     */
    protected function buildPipeline(): callable
    {
        $pipeline = $this->handle(...);

        foreach (array_reverse($this->middleware) as $middlewareClass) {
            /** @var MiddlewareInterface $middleware */
            $middleware = $this->container->make($middlewareClass);
            $next = $pipeline;
            $pipeline = static fn(Request $request, Context $context): mixed => $middleware->process(
                $request,
                $context,
                $next,
            );
        }

        return $pipeline;
    }

    /**
     * Core handler: validates the request body and evaluates it.
     *
     * @param Request $request
     * @param Context $context
     * @return mixed
     * @throws InvalidRequestException When validation fails.
     */
    protected function handle(Request $request, Context $context): mixed
    {
        // A deferred parse failure (see readRequest) is carried as the body.
        if ($request->body instanceof \Throwable) {
            throw $request->body;
        }

        $errors = $this->parser->validate($request->body, $context);

        if ($errors !== null) {
            throw new InvalidRequestException('Request validation failed.', $errors);
        }

        return $this->parser->evaluate($request->body, $context);
    }
}
