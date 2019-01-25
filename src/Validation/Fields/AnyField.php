<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Validation\Fields;

class AnyField extends BaseField
{
    /**
     * Performs child field validation.
     *
     * @param string $field Field name
     * @param mixed $value Value to be validated.
     * @param array $data All other data to be validated.
     * @return array
     */
    protected function doValidate(string $field, $value, $data, $errors): array
    {
        return $errors;
    }

    /**
     * Returns array definition of this field.
     * @return array
     */
    public function getFieldDefinition(): array
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    protected function getName(): string
    {
        return 'any';
    }

    /**
     * @inheritdoc
     */
    public function clone()
    {
        $instance = new static();
        $this->setupClone($instance);
        return $instance;
    }
}