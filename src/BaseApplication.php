<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL;

use ArekX\JsonQL\Config\ConfigInterface;
use ArekX\JsonQL\Services\Request\RequestInterface;

abstract class BaseApplication
{
    /** @var ConfigInterface */
    protected $config;

    /** @var RequestInterface  */
    protected $request;

    public function __construct(ConfigInterface $config, RequestInterface $request, array $setup)
    {
        $this->config = $config;
        $this->request = $request;

        $this->setup($setup);
    }

    public abstract function setup($values): void;

    public abstract function run(): void;
}