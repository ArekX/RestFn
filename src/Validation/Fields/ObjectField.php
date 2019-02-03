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
    const ERROR_MISSING_KEYS = 'missing_keys';
    const ERROR_NOT_AN_ASSOCIATIVE = 'not_an_associative';
    const ERROR_INVALID_FIELD_KEYS = 'invalid_field_keys';

    /**
     * Validator for fields.
     *
     * Key of the field is the field name,
     * Value of the field is the instance of FieldInterface.
     *
     * @var FieldInterface[]
     */
    public $fields;

    /**
     * Validator for any key in the value.
     *
     * @var null|FieldInterface
     */
    public $anyKey;

    /**
     * Whether or not to allow missing keys
     *
     * Defaults to false - Missing keys are not allowed.
     *
     * @var bool
     */
    public $allowMissing = false;

    /**
     * Whether or not to force strict keys.
     *
     * If strict keys are true validation will fail if there are more
     * fields in the value than which are validated.
     *
     * @var bool
     */
    public $strictKeys = false;

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
     * Sets field validator for any key which is not specified in fields.
     *
     * @param FieldInterface|null $field Field to be set.
     * @return $this
     */
    public function anyKey(?FieldInterface $field = null)
    {
        $this->anyKey = $field;
        return $this;
    }

    /**
     * Sets whether or not to allow missing keys.
     *
     * @param bool $allowMissing
     * @return $this
     */
    public function allowMissing(bool $allowMissing = true)
    {
        $this->allowMissing = $allowMissing;
        return $this;
    }

    /**
     * Sets whether or not to force that value must only have
     * keys which are validated by this field.
     *
     * @param bool $strictKeys
     * @return $this
     */
    public function strictKeys(bool $strictKeys = true)
    {
        $this->strictKeys = $strictKeys;
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
            'anyKey' => $this->anyKey ? $this->anyKey->definition() : null,
            'allowMissing' => $this->allowMissing,
            'fields' => $defs
        ];
    }

    /**
     * @inheritdoc
     */
    protected function doValidate($value, $parentValue = null): array
    {
        if (!is_array($value)) {
            return [['type' => self::ERROR_NOT_AN_ASSOCIATIVE]];
        }

        $errors = [];

        $fieldErrors = [];
        foreach ($value as $fieldName => $fieldValue) {

            /** @var FieldInterface $field */
            $field = $this->fields[$fieldName] ?? $this->anyKey;

            if ($field === null) {
                continue;
            }

            $result = $field->validate($value[$fieldName], $value);

            if (!empty($result)) {
                $fieldErrors[$fieldName] = $result;
            }
        }


        if (!empty($fieldErrors)) {
            $errors[] = ['type' => self::ERROR_INVALID_FIELDS, 'fields' => $fieldErrors];
        }

        if ($this->allowMissing === false) {
            $missingKeys = array_keys(array_diff_key($this->fields, $value));

            if (!empty($missingKeys)) {
                $errors[] = ['type' => self::ERROR_MISSING_KEYS, 'keys' => $missingKeys];
            }
        }

        if ($this->strictKeys) {
            $invalidKeys = array_keys(array_diff_key($value, $this->fields));

            if (!empty($invalidKeys)) {
                $errors[] = ['type' => self::ERROR_INVALID_FIELD_KEYS, 'keys' => $invalidKeys];
            }
        }

        return $errors;
    }
}