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
 * Class EnumField Field representing a enumeration type.
 * @package ArekX\JsonQL\Validation\Fields
 */
class EnumField extends BaseField
{
    const ERROR_NOT_VALID_VALUE = 'not_valid_value';

    public $values = [];

    public function __construct(array $values)
    {
        $this->values = $values;
    }

    /**
     * @inheritdoc
     */
    public function name(): string
    {
        return 'enum';
    }


    /**
     * @inheritdoc
     */
    protected function fieldDefinition(): array
    {
        return [
            'values' => $this->values
        ];
    }

    /**
     * @inheritdoc
     */
    protected function doValidate($value, $parentValue = null): array
    {
        if (!in_array($value, $this->values, true)) {
            return [['type' => self::ERROR_NOT_VALID_VALUE, 'valid' => $this->values]];
        }

        return [];
    }
}