<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Values;

use ArekX\JsonQL\Helpers\Validator;
use ArekX\JsonQL\Helpers\Value;
use ArekX\JsonQL\Types\BaseType;

abstract class TypedValue implements \ArrayAccess
{
    /** @var mixed */
    protected $data;

    /** @var BaseType */
    protected static $type;

    protected function __construct(array $data)
    {
        $this->setData($data);
    }

    public static function from(array $data)
    {
        return new static($data);
    }

    protected static function defaultValues()
    {
        return null;
    }

    public function setData($data)
    {
        $this->data = $data;
        $this->processType();
    }

    public function __get($name)
    {
        return $this->get($name);
    }

    public function __set($name, $value)
    {
        $this->set($name, $value);
    }

    public function __isset($name)
    {
        return $this->offsetExists($name);
    }

    public function __unset($name)
    {
        $this->offsetUnset($name);
    }

    public function get($name, $defaultValue = null)
    {
        return Value::get($this->data, $name, $defaultValue);
    }

    public function set($name, $value)
    {
        Value::set($this->data, $name, $value);
        $this->processType();
    }

    protected function processType()
    {
        $defaultValues = static::defaultValues();

        if (!empty($defaultValues)) {
            $this->data = Value::merge(static::defaultValues(), $this->data);
        }

        Validator::ensure($this->data, static::$type);
    }

    public static function definition(): array
    {
        return [
            'type' => static::$type::definition(),
            'default' => static::defaultValues()
        ];
    }

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
        unset($this->data[$offset]);
        $this->processType();
    }

    /**
     * @inheritdoc
     */
    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

}