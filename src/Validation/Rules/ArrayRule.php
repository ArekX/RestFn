<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Validation\Rules;

use ArekX\JsonQL\Validation\RuleInterface;

class ArrayRule extends BaseRule
{
    const NOT_AN_ARRAY = 'not_an_array';

    /** @var RuleInterface */
    protected $itemRule;

    public function of(RuleInterface $itemRule): ArrayRule
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

        foreach ($value as $index => $item) {
            $itemErrors = $this->itemRule->validate((string)$index, $item, $value);

            if (!empty($itemErrors)) {
                $errors[] = ['type' => 'item_invalid', 'data' => $itemErrors];
                break;
            }
        }

        return $errors;
    }
}