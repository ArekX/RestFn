<?php
/**
  * @author Aleksandar Panic
  * @link https://jsonql.readthedocs.io/
  * @license: http://www.apache.org/licenses/LICENSE-2.0
  * @since 1.0.0
 **/

namespace ArekX\JsonQL\Rest;


use ArekX\JsonQL\Helpers\DI;
use ArekX\JsonQL\Rest\Handlers\Definer;
use ArekX\JsonQL\Rest\Handlers\Performer;
use ArekX\JsonQL\Rest\Handlers\Reader;
use ArekX\JsonQL\Rest\Handlers\Sequencer;
use ArekX\JsonQL\Rest\Handlers\Writer;
use ArekX\JsonQL\Rest\Services\JsonResponse;
use ArekX\JsonQL\Rest\Services\Request;
use ArekX\JsonQL\Interfaces\RequestInterface;
use ArekX\JsonQL\Interfaces\ResponseInterface;

/**
 * Class Config
 * @package ArekX\JsonQL\Rest
 *
 * Rest configuration class.
 */
class Config extends \ArekX\JsonQL\Config\Config
{
    /**
     * @inheritdoc
     */
    protected function getCoreConfig(): array
    {
        return [
            RequestInterface::class => DI::wireClass(Request::class),
            ResponseInterface::class => DI::wireClass(JsonResponse::class),
        ];
    }

    /**
     * @inheritdoc
     */
    protected function getAppConfig()
    {
        return [
            'handlers' => [
                Performer::class,
                Reader::class,
                Writer::class,
                Definer::class,
                Sequencer::class
            ]
        ];
    }

    /**
     * Returns application class used to bootstrap the application and configure it.
     * @return string
     */
    protected function getApplicationClass(): string
    {
        return Application::class;
    }
}