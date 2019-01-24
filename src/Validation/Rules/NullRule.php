<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Validation\Rules;

class NullRule extends BaseRule
{
    const NOT_A_NULL = 'not_a_null';

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
        if (!is_null($value)) {
            $errors[] = ['type' => self::NOT_A_NULL];
        }

        return $errors;
    }
}