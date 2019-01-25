<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Values;

use ArekX\JsonQL\Helpers\Validator;
use ArekX\JsonQL\Helpers\Value;
use ArekX\JsonQL\Validation\ValidatedTypeInterface;

abstract class TypedValue implements \ArrayAccess
{
    /** @var mixed */
    protected $data;

    /** @var ValidatedTypeInterface */
    protected $type;

    protected function __construct($data, $type)
    {
        $this->type = $type;
        $this->setData($data);
    }

    public function setData($data)
    {
        $this->data = $data;
        $this->validate();
    }

    public function get($name, $defaultValue = null)
    {
        return Value::get($this->data, $name, $defaultValue);
    }

    public function set($name, $value)
    {
        Value::set($this->data, $name, $value);
        $this->validate();
    }

    protected function validate()
    {
        Validator::ensure($this->data, $this->type);
    }

    public static abstract function definition(): array;

    /**
     * @inheritdoc
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * @inheritdoc
     */
    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    /**
     * @inheritdoc
     */
    public function offsetUnset($offset)
    {
    }

    /**
     * @inheritdoc
     */
    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

}