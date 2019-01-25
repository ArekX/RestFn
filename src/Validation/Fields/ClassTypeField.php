<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Validation\Fields;

use ArekX\JsonQL\Helpers\Value;
use ArekX\JsonQL\Types\TypeInterface;
use function ArekX\JsonQL\Validation\objectType;
use ArekX\JsonQL\Validation\FieldInterface;

class ClassTypeField extends BaseField
{
    const SUBTYPE_INVALID = 'subtype_invalid';

    /** @var string */
    protected $classTypeName;

    /** @var FieldInterface[] */
    protected $overrideFields = [];

    /** @var FieldInterface[] */
    protected $fields;

    /** @var FieldInterface */
    protected $validator;

    public function __construct(string $typeClass, array $fieldConfig = [])
    {
        /** @var $typeClass TypeInterface */

        $this->fields = $typeClass::resolvedFields($fieldConfig);
        $this->classTypeName = $typeClass::name();

        $this->override();
    }

    public function override(?array $fields = null): ClassTypeField
    {
        $this->validator = objectType()
            ->of($fields === null ? $this->fields : Value::merge($this->fields, $fields))
            ->strict();

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
        $results = $this->validator->validateField($field, $value, $data);

        if (!empty($results)) {
            $errors[] = [
                'type' => self::SUBTYPE_INVALID,
                'data' => [
                    'subType' => $this->classTypeName,
                    'errors' => $results
                ]
            ];
        }

        return $errors;
    }

    /**
     * @inheritdoc
     */
    protected function getName(): string
    {
        return 'class';
    }

    /**
     * @inheritdoc
     */
    protected function getFieldDefinition(): array
    {
        return [
            'name' => $this->classTypeName,
            'definition' => $this->validator->getDefinition()
        ];
    }


    /**
     * @inheritdoc
     */
    public function clone()
    {
        $instance = new static();
        $this->setupClone($instance);
        $instance->classTypeName = $this->classTypeName;
        $instance->overrideFields = $this->overrideFields;
        $instance->fields = $this->fields;
        $instance->validator = $this->validator;
        return $instance;
    }
}