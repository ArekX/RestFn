<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Validation\Fields;

use ArekX\JsonQL\Validation\FieldInterface;

/**
 * Class AnyOfField
 * @package ArekX\JsonQL\Validation\Fields
 *
 * Field representing multiple fields which all must be true.
 *
 * This field is equivalent to the AND operator.
 */
class AnyOfField implements FieldInterface
{
    /**
     * @var FieldInterface[]
     */
    public $fields;

    public function __construct(array $fields = [])
    {
        $this->fields = $fields;
    }

    /**
     * Adds another field to the validation list.
     *
     * @param FieldInterface $field Field to be added.
     * @return AnyOfField
     */
    public function andField(FieldInterface $field): AnyOfField
    {
        $this->fields[] = $field;
        return $this;
    }

    /**
     * Adds list of fields to be validated.
     *
     * @param array $fields Fields to be validated.
     * @return AnyOfField
     */
    public function withFields(array $fields): AnyOfField
    {
        $this->fields = array_merge($this->fields, $fields);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function validate(string $field, $value)
    {
        $errors = [];

        foreach ($this->fields as $fieldValidator) {
            $results = $fieldValidator->validate($field, $value);

            if (empty($results)) {
                return [];
            }

            $errors = array_merge($errors, $results);
        }

        return $errors;
    }
}