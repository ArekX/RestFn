<?php
/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

namespace ArekX\JsonQL\Rest\Helpers;


class Field
{
    public static function normalize(array $fields): array
    {
        $normalized = [];

        foreach ($fields as $field) {
            if (is_string($field)) {
                $normalized[] = [
                    'for' => $field,
                    'as' => $field,
                    'fields' => []
                ];
                continue;
            }

            if (is_array($field)) {
                $normalized[] = [
                    'for' => $field['for'],
                    'as' => $field['as'] ?? $field['for'],
                    'fields' => !empty($field['fields']) ? static::normalize($field['fields']) : []
                ];
            }
        }

        return $normalized;
    }
}