<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace tests\Validation\Mocks;

use ArekX\JsonQL\Validation\BaseField;

class DummyField extends BaseField
{
    /**
     * Performs actual fields validation.
     *
     * If a field if not required and has an empty value. This validation is not exectued.
     *
     * @param string $field Field name to be validated.
     * @param mixed $value Value to be validated.
     * @param mixed $parentValue Parent value this field and value is in if applicable.
     * @return array List of errors if not valid or an empty array if it is valid.
     */
    protected function doValidate(string $field, $value, $parentValue = null): array
    {
        return [];
    }
}
