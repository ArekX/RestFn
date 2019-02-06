<?php
/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

namespace ArekX\JsonQL\Validation;

/**
 * Interface TypeInterface Interface representing one type.
 * @package ArekX\JsonQL\Validation
 */
interface TypeInterface
{
    /**
     * Returns fields to be used for validation.
     *
     * @return FieldInterface[]|FieldInterface
     */
    public static function fields();
}