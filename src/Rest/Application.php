<?php
/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

namespace ArekX\JsonQL\Rest;

use ArekX\JsonQL\Helpers\Value;
use ArekX\JsonQL\Rest\Handlers\HandlerInterface;
use ArekX\JsonQL\Rest\Handlers\InvalidHandlerException;

/**
 * Class Application
 *
 * Class describing a REST application.
 *
 * @package ArekX\JsonQL\Rest
 */
class Application extends \ArekX\JsonQL\MainApplication
{
    /**
     * List of request handlers.
     *
     * @var HandlerInterface[]
     */
    public $handlers = [];

    /**
     * Setups public properties in the application from setup value.
     *
     * @param $values
     */
    public function setup($values): void
    {
        Value::setup($this, $values, [
            'handlers' => []
        ]);
    }

    /**
     * Runs an application and handles one request.
     *
     * @throws InvalidHandlerException
     */
    public function run(): void
    {
        $request = $this->request->read();

        foreach ($request as $type => $data) {
            $handler = $this->getHandler($type);
            $result = $handler->handle($data);
            $this->response->write($handler, $result);
        }

        $this->response->output();
    }

    /**
     * Returns one handler based on the type.
     *
     * @param string $type Type of the handler.
     * @return HandlerInterface Instance of the handler.
     * @throws InvalidHandlerException Exception thrown if handler cannot be resolved for a type.
     */
    protected function getHandler(string $type): HandlerInterface
    {
        foreach ($this->handlers as $handlerClass) {
            if ($handlerClass::requestType() === $type) {
                return $this->config->getDI()->get($handlerClass);
            }
        }

        throw new InvalidHandlerException($type);
    }
}