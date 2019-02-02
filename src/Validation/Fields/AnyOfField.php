<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Validation\Fields;

use ArekX\JsonQL\Validation\BaseField;
use ArekX\JsonQL\Validation\FieldInterface;

/**
 * Class AnyOfField
 * @package ArekX\JsonQL\Validation\Fields
 *
 * Field representing multiple fields which all must be true.
 *
 * This field is equivalent to the AND operator.
 */
class AnyOfField extends BaseField
{
    /**
     * @var FieldInterface[]
     */
    public $fields;

    /**
     * AnyOfField constructor.
     * @param array $fields Fields to be validated.
     */
    public function __construct(array $fields = [])
    {
        $this->fields = $fields;
    }

    /**
     * @inheritdoc
     */
    public function name(): string
    {
        return 'anyOf';
    }

    /**
     * Adds another field to the validation list.
     *
     * @param FieldInterface $field Field to be added.
     * @return static
     */
    public function andField(FieldInterface $field)
    {
        $this->fields[] = $field;
        return $this;
    }

    /**
     * Adds list of fields to be validated.
     *
     * @param array $fields Fields to be validated.
     * @return static
     */
    public function withFields(array $fields)
    {
        $this->fields = array_merge($this->fields, $fields);
        return $this;
    }

    /**
     * @inheritdoc
     */
    protected function fieldDefinition(): array
    {
        $fields = [];

        foreach ($this->fields as $field) {
            $fields[] = $field->definition();
        }

        return [
            'fields' => $fields
        ];
    }

    /**
     * @inheritdoc
     */
    public function doValidate(string $field, $value, $parentValue = null): array
    {
        $errors = [];

        foreach ($this->fields as $fieldValidator) {
            $results = $fieldValidator->validate($field, $value, $parentValue);

            if (empty($results)) {
                return [];
            }

            $errors = array_merge($errors, $results);
        }

        return $errors;
    }
}