<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Validation;

class InvalidTypeException extends \Exception
{
    protected $errors;

    public function __construct(array $errors, $code = 0, $previous = null)
    {
        $this->errors = $errors;
        parent::__construct('Invalid type', $code, $previous);
    }

    public function getErrors()
    {
        return $this->errors;
    }
}