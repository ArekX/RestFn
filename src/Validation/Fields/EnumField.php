<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Validation\Fields;

class EnumField extends BaseField
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

    /**
     * @inheritdoc
     */
    protected function getName(): string
    {
        return 'enum';
    }

    /**
     * @inheritdoc
     */
    protected function getFieldDefinition(): array
    {
        return [
            'values' => $this->values
        ];
    }

    /**
     * @inheritdoc
     */
    public function clone()
    {
        $instance = new static($this->values);
        $this->setupClone($instance);
        return $instance;
    }
}