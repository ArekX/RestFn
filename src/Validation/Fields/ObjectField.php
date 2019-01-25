<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Validation\Fields;

use ArekX\JsonQL\Helpers\Value;
use ArekX\JsonQL\Validation\FieldInterface;
use ArekX\JsonQL\Validation\Fields\BaseField;

class ObjectField extends BaseField
{
    const INVALID_TYPE = 'invalid_object';
    const ANY_KEY = '[key]';
    const INVALID_KEYS = 'invalid_keys';
    const MISSING_KEYS = 'missing_keys';
    const NOT_AN_OBJECT = 'not_an_object';

    /** @var FieldInterface[] */
    public $fields = [];

    /**
     * Set fields of this object.
     *
     * @param array $fields
     * @return static
     */
    public function of(array $fields): ObjectField
    {
        $this->fields = $fields;
        return $this;
    }

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
        if (empty($this->fields) && !$this->strict) {
            return $errors;
        }

        if (!is_array($value)) {
            $errors[] = ['type' => self::NOT_AN_OBJECT];
            return $errors;
        }

        if ($this->strict) {
            $this->validateValidKeys(array_keys($value), $errors);
        }

        $this->validateFields($value, $errors);
        $this->validateAnyKey($value, $errors);

        return $errors;

    }

    protected function validateFields(array &$data, array &$errors)
    {
        $results = [];

        foreach ($this->fields as $fieldName => $validator) {
            if ($fieldName === self::ANY_KEY) {
                continue;
            }
            $this->validateSubField($fieldName, $validator, $data, $results);
        }

        $this->validateAnyKey($data, $results);

        if (!empty($results)) {
            $errors[] = ['type' => self::INVALID_TYPE, 'data' => $results];
        }
    }

    protected function validateSubField($field, FieldInterface $validator, array &$data, array &$errors)
    {
        $fieldErrors = $validator->validateField($field, Value::get($data, $field), $data);

        if (!empty($fieldErrors)) {
            $errors[$field] = $fieldErrors;
        }
    }


    protected function validateAnyKey(array &$data, array &$errors): void
    {
        $anyKeyValidator = $this->getAnyKeyValidator();

        if ($anyKeyValidator === null) {
            return;
        }

        $fieldNames = array_keys($this->fields);

        foreach ($data as $key => $value) {
            if (!in_array($key, $fieldNames)) {
                $this->validateSubField($key, $anyKeyValidator, $data, $errors);
            }
        }
    }

    protected function validateValidKeys(array $dataKeys, array &$errors)
    {
        if ($this->getAnyKeyValidator() !== null) {
            return;
        }

        $checkKeys = array_keys($this->fields);

        $missingKeys = [];
        $invalidKeys = [];
        foreach ($checkKeys as $key) {
            if (!in_array($key, $dataKeys)) {
                $missingKeys[] = $key;
            }
        }

        foreach ($dataKeys as $key) {
            if (!in_array($key, $checkKeys)) {
                $invalidKeys[] = $key;
            }
        }

        if (!empty($missingKeys)) {
            $errors[] = [
                'type' => self::MISSING_KEYS,
                'data' => ['keys' => $missingKeys]
            ];
        }

        if (!empty($invalidKeys)) {
            $errors[] = [
                'type' => self::INVALID_KEYS,
                'data' => ['keys' => $invalidKeys]
            ];
        }
    }

    protected function getAnyKeyValidator(): ?FieldInterface
    {
        return $this->fields[self::ANY_KEY] ?? null;
    }

    /**
     * @inheritdoc
     */
    protected function getName(): string
    {
        return 'object';
    }

    /**
     * @inheritdoc
     */
    protected function getFieldDefinition(): array
    {
        $fields = [];
        foreach ($this->fields as $fieldName => $field) {
            $fields[$fieldName] = $field->getDefinition();
        }

        return [
            'fields' => $fields
        ];
    }

    /**
     * @inheritdoc
     */
    public function clone()
    {
        $instance = new static();
        $this->setupClone($instance);
        $instance->fields = $this->fields;
        return $instance;
    }
}