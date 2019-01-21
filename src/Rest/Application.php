<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Rest;

use ArekX\JsonQL\Helpers\Value;
use ArekX\JsonQL\Rest\Handlers\HandlerInterface;
use ArekX\JsonQL\Rest\Handlers\InvalidHandlerException;

class Application extends \ArekX\JsonQL\MainApplication
{
    /** @var HandlerInterface[] */
    public $handlers = [];

    public function setup($values): void
    {
        Value::setup($this, $values, [
            'handlers' => []
        ]);
    }

    public function run(): void
    {
        $request = $this->request->getBody();

        foreach ($request as $type => $data) {
            $handler = $this->getHandler($type);
            $this->response->write($handler, $handler->handle($data));
        }

        $this->response->output();
    }

    protected function getHandler($type): HandlerInterface
    {
        foreach ($this->handlers as $handlerClass) {
            if ($handlerClass::getRequestType() === $type) {
                return $this->config->getDI()->get($handlerClass);
            }
        }

        throw new InvalidHandlerException($type);
    }
}