<?php
/**
  * @author Aleksandar Panic
  * @link https://jsonql.readthedocs.io/
  * @license: http://www.apache.org/licenses/LICENSE-2.0
  * @since 1.0.0
 **/

namespace ArekX\JsonQL\Validation;

/**
 * Interface FieldInterface Field interface for all of the fields used for validation.
 * @package ArekX\JsonQL\Validation
 */
interface FieldInterface
{
    /**
     * Validates one fields value using this validator.
     *
     * @param string $field Name of the field to be validated.
     * @param mixed $value Value to be validated.
     * @param mixed $parentValue Parent value to be used in further validation.
     * @return array List of failed validations for this field or empty array if it is valid.
     */
    public function validate(string $field, $value, $parentValue = null): array;

    /**
     * Sets whether or not this field is required.
     *
     * @param bool $isRequired
     * @return static
     */
    public function required(bool $isRequired = true);

    /**
     * Set information about this field.
     *
     * @param string $info
     * @return static
     */
    public function info(string $info);

    /**
     * Sets example of this field.
     *
     * @param mixed $example
     * @return static
     */
    public function example($example);

    /**
     * Sets empty value for required checking.
     *
     * @param mixed $emptyValue Empty value to be set.
     * @return static
     */
    public function emptyValue($emptyValue = null);

    /**
     * Returns this fields definition.
     *
     * @return array
     */
    public function definition(): array;
}