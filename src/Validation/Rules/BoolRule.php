<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Validation\Rules;

class BoolRule extends BaseRule
{
    const NOT_A_BOOL = 'not_a_bool';

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
        if (!is_bool($value)) {
            $errors[] = ['type' => self::NOT_A_BOOL];
        }

        return $errors;
    }
}