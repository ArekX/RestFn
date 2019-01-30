<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Validation\Fields;

use ArekX\JsonQL\Validation\FieldInterface;

/**
 * Class AllOfField
 * @package ArekX\JsonQL\Validation\Fields
 *
 * Field representing multiple fields which all must be true.
 *
 * This field is equivalent to the AND operator.
 */
class AllOfField implements FieldInterface
{
    /**
     * @var FieldInterface[] List of fields which will be validated.
     */
    public $fields;

    /**
     * AllOfField constructor.
     *
     * @param array $fields Fields which will be validated.
     */
    public function __construct(array $fields)
    {
        $this->fields = $fields;
    }

    /**
     * Adds another field to the validation list.
     *
     * @param FieldInterface $field Field to be added.
     * @return AllOfField
     */
    public function andField(FieldInterface $field): AllOfField
    {
        $this->fields[] = $field;
        return $this;
    }


    public function withFields(array $fields): AllOfField
    {
        $this->fields = array_merge($this->fields, $fields);
        return $this;
    }
}