<?php
/**
  * @author Aleksandar Panic
  * @link https://jsonql.readthedocs.io/
  * @license: http://www.apache.org/licenses/LICENSE-2.0
  * @since 1.0.0
 **/

namespace ArekX\JsonQL\Validation\Fields;

use ArekX\JsonQL\Validation\BaseField;

/**
 * Class AnyField Field representing a any type.
 * @package ArekX\JsonQL\Validation\Fields
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
    protected function doValidate($value, $parentValue = null): array
    {
        return [];
    }
}