<?php
/**
  * @author Aleksandar Panic
  * @link https://jsonql.readthedocs.io/
  * @license: http://www.apache.org/licenses/LICENSE-2.0
  * @since 1.0.0
 **/

namespace ArekX\JsonQL\Validation\Fields;

use ArekX\JsonQL\Validation\BaseField;
use ArekX\JsonQL\Validation\FieldInterface;

/**
 * Class ArrayField Field representing a array type.
 * @package ArekX\JsonQL\Validation\Fields
 */
class ArrayField extends BaseField
{
    const ERROR_NOT_AN_ARRAY = 'not_an_array';
    const ERROR_ITEM_NOT_VALID = 'item_not_valid';

    /**
     * Item type which will be checked.
     *
     * Defaults to null - any item type is allowed.
     *
     * @var null|FieldInterface
     */
    public $of = null;

    /**
     * Sets item type which will be checked
     *
     * @param FieldInterface $field
     * @return ArrayField
     */
    public function of(?FieldInterface $field = null)
    {
        $this->of = $field;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function name(): string
    {
        return 'array';
    }


    /**
     * @inheritdoc
     */
    protected function fieldDefinition(): array
    {
        return [
            'itemType' => $this->of ? $this->of->definition() : null
        ];
    }

    /**
     * @inheritdoc
     */
    protected function doValidate($value, $parentValue = null): array
    {
        if (!is_array($value)) {
            return [['type' => self::ERROR_NOT_AN_ARRAY]];
        }

        if ($this->of === null) {
            return [];
        }

        $errorItems  = [];

        foreach ($value as $key => $item) {
            $errors = $this->of->validate($item, $value);

            if (!empty($errors)) {
                $errorItems[$key] = $errors;
            }
        }

        if (!empty($errorItems)) {
            return [
                ['type' => self::ERROR_ITEM_NOT_VALID, 'items' => $errorItems]
            ];
        }

        return [];
    }
}