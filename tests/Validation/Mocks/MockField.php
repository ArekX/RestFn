<?php
/**
  * @author Aleksandar Panic
  * @link https://jsonql.readthedocs.io/
  * @license: http://www.apache.org/licenses/LICENSE-2.0
  * @since 1.0.0
 **/

namespace tests\Validation\Mocks;

use ArekX\JsonQL\Validation\BaseField;

class MockField extends BaseField
{
    public $definition = [];

    public $validation = [];

    public function __construct($validation = [], $definition = [])
    {
        $this->definition = $definition;
        $this->validation = $validation;
    }

    /**
     * @inheritdoc
     */
    protected function doValidate(string $field, $value, $parentValue = null): array
    {
        return $this->validation;
    }

    /**
     * @inheritdoc
     */
    protected function fieldDefinition(): array
    {
        return $this->definition;
    }

    /**
     * Returns name of this field.
     *
     * @return string
     */
    public function name(): string
    {
        return 'mock';
    }
}
