<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace Rest\Handlers;

class InvalidHandlerException extends \Exception
{
    public function __construct(string $handler, $code = 0, $previous = null)
    {
        parent::__construct("Invalid handler type: {$handler}", $code, $previous);
    }
}