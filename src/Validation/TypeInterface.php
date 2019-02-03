<?php
/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license: http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

namespace ArekX\JsonQL\Validation;

use ArekX\JsonQL\Validation\Fields\ObjectField;

/**
 * Interface TypeInterface Interface representing one type.
 * @package ArekX\JsonQL\Validation
 */
interface TypeInterface
{
    /**
     * Returns name of the type.
     *
     * @return string
     */
    public static function typeName(): string;

    /**
     * Returns fields to be used for validation.
     *
     * Fields are in format:
     * ```php
     * [
     *    'key' => stringField(),
     *    'key2' => numberField(),
     *    ...
     * ]
     * ```
     *
     * @return FieldInterface[]
     */
    public static function fields(): array;

    /**
     * Returns definition of the type.
     *
     * @return array
     */
    public static function definition(): array;

    /**
     * Returns validator instance.
     *
     * @return ObjectField
     */
    public static function validator(): ObjectField;
}