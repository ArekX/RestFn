<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Validation\Fields;

use function ArekX\JsonQL\Validation\anyField;
use ArekX\JsonQL\Validation\FieldInterface;

class ArrayField extends BaseField
{
    const NOT_AN_ARRAY = 'not_an_array';
    const ITEM_INVALID = 'item_invalid';

    /** @var FieldInterface */
    protected $itemRule;

    /**
     * Sets type of the item in array.
     *
     * @param FieldInterface $itemRule
     * @return ArrayField
     */
    public function of(FieldInterface $itemRule): ArrayField
    {
        $this->itemRule = $itemRule;
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
        if (!is_array($value)) {
            $errors[] = ['type' => self::NOT_AN_ARRAY];
        }

        if (!$this->itemRule) {
            return $errors;
        }

        foreach ($value as $index => $item) {
            $itemErrors = $this->itemRule->validateField((string)$index, $item, $value);

            if (!empty($itemErrors)) {
                $errors[] = ['type' => self::ITEM_INVALID, 'data' => $itemErrors];
                break;
            }
        }

        return $errors;
    }

    /**
     * Returns array definition of this field.
     * @return array
     */
    public function getFieldDefinition(): array
    {
        return [
            'item' => ($this->itemRule ? $this->itemRule : anyField())->getDefinition()
        ];
    }

    /**
     * @inheritdoc
     */
    protected function getName(): string
    {
        return 'array';
    }

    /**
     * @inheritdoc
     */
    public function clone()
    {
        $instance = new static();
        $this->setupClone($instance);
        $instance->itemRule = $this->itemRule;
        return $instance;
    }
}