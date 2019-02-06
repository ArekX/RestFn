<?php
/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

namespace tests\Validation\Mocks;

use ArekX\JsonQL\Validation\BaseField;

/**
 * Class MockField
 * @package tests\Validation\Mocks
 */
class MockField extends BaseField
{
    /**
     * @var array
     */
    public $definition = [];

    /**
     * @var array
     */
    public $validation = [];

    /**
     * MockField constructor.
     * @param array $validation
     * @param array $definition
     */
    public function __construct($validation = [], $definition = [])
    {
        $this->definition = $definition;
        $this->validation = $validation;
    }

    /**
     * @inheritdoc
     */
    protected function doValidate($value, $parentValue = null): array
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
