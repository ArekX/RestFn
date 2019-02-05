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
     * @param mixed $value Value to be validated.
     * @param mixed $parentValue Parent value to be used in further validation.
     * @return array List of failed validations for this field or empty array if it is valid.
     */
    public function validate($value, $parentValue = null): array;

    /**
     * Returns this fields definition.
     *
     * @return array
     */
    public function definition(): array;

    /**
     * Custom identifier of the field.
     *
     * @param string $identifier Identifier name
     * @return $this
     */
    public function identifier(string $identifier);

    /**
     * Set identifier to be returned.
     * @return null|string
     */
    public function getIdentifier(): ?string;
}