<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Validation\Rules;

use ArekX\JsonQL\Helpers\Value;
use ArekX\JsonQL\Validation\RuleInterface;
use ArekX\JsonQL\Validation\Rules\BaseRule;

class ObjectRule extends BaseRule
{
    const INVALID_TYPE = 'invalid_object';
    const ANY_KEY = '[key]';
    const INVALID_KEYS = 'invalid_keys';

    /** @var RuleInterface[] */
    public $fields;

    protected $strict = null;

    public function __construct(array $object)
    {
        $this->fields = $object;
    }

    public function strict(?bool $strict = true): ObjectRule
    {
        $this->strict = $strict;
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
        $this->validateFields($data, $errors);
        $this->validateAnyKey($data, $errors);

        if ($this->strict) {
            $this->validateValidKeys(array_keys($data));
        }

        return $errors;

    }

    protected function validateFields(array &$data, array &$errors)
    {
        $results = [];

        foreach ($this->fields as $fieldName => $validator) {
            $this->validateField($fieldName, $validator, $data, $results);
        }

        $this->validateAnyKey($data, $results);

        if (!empty($results)) {
            $errors[] = ['type' => self::INVALID_TYPE, 'data' => $results];
        }
    }

    protected function validateField($field, RuleInterface $validator, array &$data, array &$errors)
    {
        $fieldErrors = $validator->validate($field, Value::get($data, $field), $data);

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
                $this->validateField($key, $anyKeyValidator, $data, $errors);
            }
        }
    }

    protected function validateValidKeys(array $dataKeys, array &$errors)
    {
        if ($this->getAnyKeyValidator() !== null) {
            return;
        }

        $invalidKeys = array_diff(array_keys($this->fields), $dataKeys);

        if (count($invalidKeys) > 0) {
            $errors[] = [
                'type' => self::INVALID_KEYS,
                'data' => ['keys' => $invalidKeys]
            ];
        }
    }

    protected function getAnyKeyValidator(): ?RuleInterface
    {
        return $this->fields[self::ANY_KEY] ?? null;
    }
}