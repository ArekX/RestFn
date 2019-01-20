<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Rest;

use ArekX\JsonQL\BaseApplication;
use ArekX\JsonQL\Helpers\Value;
use ArekX\JsonQL\Rest\Handlers\HandlerInterface;
use Rest\Handlers\InvalidHandlerException;

class Application extends BaseApplication
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

        $responses = [];

        foreach ($request as $type => $data) {
            $handler = $this->getHandler($type);
            $responses[$handler->getResponseType()] = $handler->handle($data);
        }

        echo json_encode($responses);
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