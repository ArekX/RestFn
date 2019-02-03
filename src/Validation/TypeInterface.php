<?php
/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license: http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

namespace ArekX\JsonQL\Validation;

/**
 * Interface TypeInterface Interface representing one type.
 * @package ArekX\JsonQL\Validation
 */
interface TypeInterface
{
    public static function typeName(): string ;
    public static function fields(): array;
    public static function validator(): FieldInterface;
}