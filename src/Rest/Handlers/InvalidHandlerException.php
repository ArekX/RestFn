<?php
/**
  * @author Aleksandar Panic
  * @link https://jsonql.readthedocs.io/
  * @license: http://www.apache.org/licenses/LICENSE-2.0
  * @since 1.0.0
 **/

namespace ArekX\JsonQL\Rest\Handlers;

class InvalidHandlerException extends \Exception
{
    public function __construct(string $handler, $code = 0, $previous = null)
    {
        parent::__construct("Invalid handler type: {$handler}", $code, $previous);
    }
}