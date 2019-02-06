<?php
/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

namespace ArekX\JsonQL;

use ArekX\JsonQL\Config\ConfigInterface;
use ArekX\JsonQL\Interfaces\RequestInterface;
use ArekX\JsonQL\Interfaces\ResponseInterface;

abstract class MainApplication
{
    /** @var ConfigInterface */
    protected $config;

    /** @var RequestInterface  */
    protected $request;

    /** @var ResponseInterface  */
    protected $response;

    public function __construct(
        ConfigInterface $config,
        RequestInterface $request,
        ResponseInterface $response,
        array $setup
    )
    {
        $this->config = $config;
        $this->request = $request;
        $this->response = $response;

        $this->setup($setup);
    }

    public abstract function setup($values): void;

    public abstract function run(): void;
}