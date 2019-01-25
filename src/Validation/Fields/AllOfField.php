<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Validation\Fields;

use ArekX\JsonQL\Validation\FieldInterface;

class AllOfField extends BaseField
{
    /** @var FieldInterface[] */
    protected $childFields = [];

    public function __construct(array $childRules)
    {
        $this->childFields = $childRules;
    }

    /**
     * @inheritdoc
     */
    protected function doValidate(string $field, $value, $data, $errors): array
    {
        foreach ($this->childFields as $childField) {
            $childErrors = $childField->validateField($field, $value, $data);

            if (empty($childErrors)) {
                continue;
            }

            return array_merge($errors, $childErrors);
        }

        return $errors;
    }

    /**
     * Returns array definition of this field.
     * @return array
     */
    public function getFieldDefinition(): array
    {
        return [
            'children' => array_map(function(FieldInterface $field) {
                return $field->getDefinition();
            }, $this->childFields)
        ];
    }

    /**
     * @inheritdoc
     */
    protected function getName(): string
    {
        return 'allOf';
    }

    /**
     * @inheritdoc
     */
    public function clone()
    {
        $instance = new static($this->childFields);
        $this->setupClone($instance);
        return $instance;
    }
}