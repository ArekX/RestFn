<?php
/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license: http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

namespace ArekX\JsonQL\Validation;

use Throwable;

/**
 * Class MissingIdentifierException
 *
 * Exception thrown when there is a missing identifier in a field which requires it.
 *
 * @package ArekX\JsonQL\Validation
 */
class MissingIdentifierException extends \Exception
{
    /**
     * Field which caused the exception.
     *
     * @var FieldInterface
     */
    public $field;

    /**
     * MissingIdentifierException constructor.
     * @param FieldInterface $field
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(FieldInterface $field, int $code = 0, Throwable $previous = null)
    {
        $this->field = $field;
        parent::__construct('missing_identifier', $code, $previous);
    }
}