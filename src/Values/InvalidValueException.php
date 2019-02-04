<?php
/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license: http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

namespace ArekX\JsonQL\Values;

class InvalidValueException extends \Exception
{
    public $validationErrors;

    public function __construct(array $validationErrors, $code = 0, $previous = null)
    {
        $this->validationErrors = $validationErrors;
        parent::__construct('invalid_value', $code, $previous);
    }
}