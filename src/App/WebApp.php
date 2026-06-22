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

namespace ArekX\RestFn\App;

use ArekX\RestFn\App\Contracts\ApplicationInterface;
use ArekX\RestFn\DI\Container;
use ArekX\RestFn\Parser\Contracts\EvaluatorInterface;
use ArekX\RestFn\Parser\Ops\AndOp;
use ArekX\RestFn\Parser\Ops\CastOp;
use ArekX\RestFn\Parser\Ops\CoalesceOp;
use ArekX\RestFn\Parser\Ops\CompareOp;
use ArekX\RestFn\Parser\Ops\GetOp;
use ArekX\RestFn\Parser\Ops\IfElseOp;
use ArekX\RestFn\Parser\Ops\ListOp;
use ArekX\RestFn\Parser\Ops\MapOp;
use ArekX\RestFn\Parser\Ops\MergeOp;
use ArekX\RestFn\Parser\Ops\NotOp;
use ArekX\RestFn\Parser\Ops\ObjectOp;
use ArekX\RestFn\Parser\Ops\OrOp;
use ArekX\RestFn\Parser\Ops\RunOp;
use ArekX\RestFn\Parser\Ops\SequenceOp;
use ArekX\RestFn\Parser\Ops\SortOp;
use ArekX\RestFn\Parser\Ops\TakeOp;
use ArekX\RestFn\Parser\Ops\ValueOp;
use ArekX\RestFn\Parser\Ops\VarOp;
use ArekX\RestFn\Parser\Parser;
use ArekX\RestFn\Runner\Contracts\RequestParserInterface;
use ArekX\RestFn\Runner\Contracts\ResponseInterface;
use ArekX\RestFn\Runner\JsonRequestParser;
use ArekX\RestFn\Runner\JsonResponse;
use ArekX\RestFn\Runner\Middleware\AuthenticationMiddleware;
use ArekX\RestFn\Runner\Middleware\ErrorMiddleware;
use ArekX\RestFn\Runner\Runner;
use ArekX\RestFn\Services\Auth\ClaimsAuthenticator;
use ArekX\RestFn\Services\Auth\Contracts\AuthenticatorInterface;
use ArekX\RestFn\Services\Auth\Contracts\IdentityServiceInterface;
use ArekX\RestFn\Services\Auth\Contracts\TokenParserInterface;
use ArekX\RestFn\Services\Auth\IdentityService;
use ArekX\RestFn\Services\Auth\JwtTokenParser;

/**
 * Class WebApp
 * @package ArekX\RestFn\App
 *
 * Default web application. It is resolved from the container (receiving the
 * Runner through injection) and simply runs the request to a result.
 *
 * Bootstrap with the static createDefault(), which builds the container from the
 * default bindings merged with your config and resolves the application. You then
 * call run() on it. Because the application is resolved through
 * ApplicationInterface, you can swap it by aliasing that interface to your own
 * class.
 *
 * Configuration normally goes under 'global', which every class reads from. Use
 * 'overrides' only when one specific class needs a different value.
 *
 * Typical index.php:
 * ```php
 * use ArekX\RestFn\App\WebApp;
 *
 * echo WebApp::createDefault([
 *     'aliases' => [
 *         AuthenticatorInterface::class => App\MyAuthenticator::class, // override a default
 *     ],
 *     'config' => [
 *         'global' => [
 *             'actions' => [...],
 *             'auth'    => ['jwt' => ['secret' => getenv('JWT_SECRET')]],
 *         ],
 *     ],
 * ])->run();
 * ```
 *
 * Error handling and authentication middleware are wired by default; all
 * built-in operations are registered. Set 'runner.middleware' or 'ops' only when
 * you want to take control of those.
 */
class WebApp implements ApplicationInterface
{
    /**
     * Default interface-to-implementation bindings. Override any of them by
     * passing your own 'aliases' to createDefault() - including
     * ApplicationInterface to replace the application itself.
     */
    public const DEFAULT_ALIASES = [
        ApplicationInterface::class => WebApp::class,
        EvaluatorInterface::class => Parser::class,
        RequestParserInterface::class => JsonRequestParser::class,
        ResponseInterface::class => JsonResponse::class,
        TokenParserInterface::class => JwtTokenParser::class,
        IdentityServiceInterface::class => IdentityService::class,
        AuthenticatorInterface::class => ClaimsAuthenticator::class,
    ];

    /**
     * All built-in operations, as a map of operation name to class. These are
     * made available by default so a client can use the full operation language
     * without registering each one. Set the 'ops' config value to a narrower map
     * to restrict what is allowed.
     */
    public const DEFAULT_OPS = [
        'and' => AndOp::class,
        'cast' => CastOp::class,
        'coalesce' => CoalesceOp::class,
        'compare' => CompareOp::class,
        'get' => GetOp::class,
        'ifElse' => IfElseOp::class,
        'list' => ListOp::class,
        'map' => MapOp::class,
        'merge' => MergeOp::class,
        'not' => NotOp::class,
        'object' => ObjectOp::class,
        'or' => OrOp::class,
        'run' => RunOp::class,
        'sequence' => SequenceOp::class,
        'sort' => SortOp::class,
        'take' => TakeOp::class,
        'value' => ValueOp::class,
        'var' => VarOp::class,
    ];

    /**
     * Default middleware stack, outermost first. Error handling wraps everything
     * so any failure becomes a clean response, and authentication establishes the
     * identity from a bearer token when one is present. Set the
     * 'runner.middleware' config value to take full control of the stack.
     */
    public const DEFAULT_MIDDLEWARE = [
        ErrorMiddleware::class,
        AuthenticationMiddleware::class,
    ];

    /**
     * @param Runner $runner Runner used to handle the request.
     */
    public function __construct(
        protected Runner $runner,
    ) {}

    /**
     * @inheritDoc
     */
    #[\Override]
    public function run(): mixed
    {
        return $this->runner->run();
    }

    /**
     * Bootstraps the application with the default bindings and returns it.
     *
     * Builds a container from the default aliases merged with the given config
     * (the config's aliases take precedence) and resolves the application through
     * ApplicationInterface. Call run() on the returned application to handle the
     * request.
     *
     * @param array $config Container configuration, merged over the defaults.
     * @return ApplicationInterface The resolved application.
     */
    public static function createDefault(array $config = []): ApplicationInterface
    {
        $config['aliases'] = ($config['aliases'] ?? []) + self::DEFAULT_ALIASES;
        $config['config']['global']['ops'] = $config['config']['global']['ops'] ?? self::DEFAULT_OPS;
        $config['config']['global']['runner']['middleware'] =
            $config['config']['global']['runner']['middleware'] ?? self::DEFAULT_MIDDLEWARE;

        $container = new Container($config);

        return $container->make(ApplicationInterface::class);
    }
}
