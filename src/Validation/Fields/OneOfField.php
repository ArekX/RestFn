<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Validation\Fields;

use ArekX\JsonQL\Validation\FieldInterface;

class OneOfField extends BaseField
{
    /** @var FieldInterface[] */
    protected $childRules = [];

    public function __construct(array $childRules)
    {
        $this->childRules = $childRules;
    }

    /**
     * @inheritdoc
     */
    protected function doValidate(string $field, $value, $data, $errors): array
    {
        $allChildrenErrors = [];

        foreach ($this->childRules as $childRule) {
            $childErrors = $childRule->validateField($field, $value, $data);

            if (empty($childErrors)) {
                return $errors;
            }

            $allChildrenErrors = array_merge($allChildrenErrors, $childErrors);
        }

        return $errors;
    }

    /**
     * @inheritdoc
     */
    protected function getName(): string
    {
        return 'oneOf';
    }

    /**
     * @inheritdoc
     */
    protected function getFieldDefinition(): array
    {
        return [
            'children' => array_map(function(FieldInterface $field) {
                return $field->getDefinition();
            }, $this->childRules)
        ];
    }


    /**
     * @inheritdoc
     */
    public function clone()
    {
        $instance = new static();
        $this->setupClone($instance);
        $instance->childRules = $this->childRules;
        return $instance;
    }
}