<?php
/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

namespace ArekX\JsonQL\Validation\Fields;

use ArekX\JsonQL\Validation\BaseField;

/**
 * Class BoolField Field representing a boolean type.
 * @package ArekX\JsonQL\Validation\Fields
 */
class BoolField extends BaseField
{
    const ERROR_NOT_A_BOOL = 'not_a_bool';

    /**
     * @inheritdoc
     */
    public function name(): string
    {
        return 'bool';
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
    protected function doValidate($value, $parentValue = null): array
    {
        if (!is_bool($value)) {
            return [self::ERROR_NOT_A_BOOL => true];
        }

        return [];
    }
}