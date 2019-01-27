<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Validation\Fields;

use ArekX\JsonQL\Validation\FieldInterface;

class OneOfField extends BaseField
{
    /** @var FieldInterface[] */
    protected $childFields = [];

    public function __construct(array $fields)
    {
        $this->childFields = $fields;
    }

    /**
     * @inheritdoc
     */
    protected function doValidate(string $field, $value, $data, $errors): array
    {
        $allChildrenErrors = [];

        foreach ($this->childFields as $childRule) {
            $childErrors = $childRule->validateField($field, $value, $data);

            if (empty($childErrors)) {
                return $errors;
            }

            $allChildrenErrors = array_merge($allChildrenErrors, $childErrors);
        }

        return $errors;
    }

    /**
     * @inheritdoc
     */
    protected function getName(): string
    {
        return 'oneOf';
    }

    /**
     * @inheritdoc
     */
    protected function getFieldDefinition(): array
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
    public function writeDefaultValue(&$data): void
    {
        foreach ($this->childFields as $childField) {
            $childField->writeDefaultValue($data);
        }
    }

    /**
     * @inheritdoc
     */
    public function clone()
    {
        $instance = new static();
        $this->setupClone($instance);
        $instance->childFields = $this->childFields;
        return $instance;
    }
}