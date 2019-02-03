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
 * Class ObjectField Field representing a object type.
 *
 * @package ArekX\JsonQL\Validation\Fields
 */
class ObjectField extends BaseField
{
    const ERROR_INVALID_FIELDS = 'invalid_fields';

    /** @var FieldInterface[] */
    public $fields;

    /**
     * ObjectField constructor.
     * @param array $fields Fields to be passed to `fields()` function.
     * @see ObjectField::fields()
     */
    public function __construct(array $fields = [])
    {
        $this->fields($fields);
    }

    /**
     * Sets fields which will be validated.
     *
     * Fields are passed in key => value format where key is the
     * name of the field and value is an instance of FieldInterface
     *
     * @see FieldInterface
     * @param array $fields
     * @return $this
     */
    public function fields(array $fields)
    {
        $this->fields = $fields;
        return $this;
    }


    /**
     * @inheritdoc
     */
    public function name(): string
    {
        return 'object';
    }


    /**
     * @inheritdoc
     */
    protected function fieldDefinition(): array
    {
        $defs = [];

        foreach ($this->fields as $fieldName => $field) {
            $defs[$fieldName] = $field->definition();
        }

        return [
            'fields' => $defs
        ];
    }

    /**
     * @inheritdoc
     */
    protected function doValidate($value, $parentValue = null): array
    {
        $fieldErrors = [];
        foreach ($this->fields as $fieldName => $field) {
            $result = $field->validate($value[$fieldName], $value);

            if (!empty($result)) {
                $fieldErrors[$fieldName] = $result;
            }
        }

        if (!empty($fieldErrors)) {
            return [['type' => self::ERROR_INVALID_FIELDS, 'fields' => $fieldErrors]];
        }

        return [];
    }
}