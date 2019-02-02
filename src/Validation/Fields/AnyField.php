<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Validation\Fields;

use ArekX\JsonQL\Validation\BaseField;

/**
 * Class AnyField
 * @package ArekX\JsonQL\Validation\Fields
 *
 * Field representing a any type.
 *
 */
class AnyField extends BaseField
{
    /**
     * @inheritdoc
     */
    public function name(): string
    {
        return 'any';
    }


    /**
     * @inheritdoc
     */
    protected function fieldDefinition(): array
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    protected function doValidate(string $field, $value, $parentValue = null): array
    {
        return [];
    }
}