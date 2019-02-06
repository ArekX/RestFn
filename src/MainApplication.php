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

/**
 * Class MainApplication
 *
 * Abstract class representing any type of JsonQL application.
 *
 * @package ArekX\JsonQL
 */
abstract class MainApplication
{
    /**
     * Config interface containing configuration for the app.
     *
     * @var ConfigInterface
     */
    protected $config;

    /**
     * Request instance for handing one request.
     *
     * @var RequestInterface
     */
    protected $request;

    /**
     * Response instance for writing responses.
     *
     * @var ResponseInterface
     */
    protected $response;

    /**
     * MainApplication constructor.
     *
     * @param ConfigInterface $config
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param array $setup
     */
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

    /**
     * Setup public values to provide additional configuration for the app.
     *
     * @param $values
     */
    public abstract function setup($values): void;

    /**
     * Executes the app.
     */
    public abstract function run(): void;
}