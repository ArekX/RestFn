<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Validation\Rules;

class EnumRule extends BaseRule
{
    const NOT_IN_ENUM = 'not_in_enum';

    protected $values;

    public function __construct(array $values)
    {
        $this->values = $values;
    }


    /**
     * @inheritdoc
     */
    protected function doValidate(string $field, $value, $data, $errors): array
    {
        if (!in_array($value, $this->values)) {
            $errors[] = ['type' => self::NOT_IN_ENUM, 'data' => ['enum' => $this->values]];
        }

        return $errors;
    }
}