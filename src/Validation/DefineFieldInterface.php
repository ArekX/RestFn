<?php
/**
  * @author Aleksandar Panic
  * @link https://jsonql.readthedocs.io/
  * @license: http://www.apache.org/licenses/LICENSE-2.0
  * @since 1.0.0
 **/

namespace ArekX\JsonQL\Validation;

/**
 * Interface DefineFieldInterface Field interface for all of the fields used for validation.
 * @package ArekX\JsonQL\Validation
 */
interface DefineFieldInterface
{
    /**
     * Sets whether or not this field cannot have empty value..
     *
     * @param bool $allowEmpty
     * @return $this
     */
    public function allowEmpty(bool $allowEmpty = true);

    /**
     * Set information about this field.
     *
     * @param string $info
     * @return $this
     */
    public function info(string $info);

    /**
     * Sets example of this field.
     *
     * @param mixed $example
     * @return $this
     */
    public function example($example);

    /**
     * Sets empty value for required checking.
     *
     * @param mixed $emptyValue Empty value to be set.
     * @return $this
     */
    public function emptyValue($emptyValue = null);
}