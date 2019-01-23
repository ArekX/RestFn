<?php
/**
 * by Aleksandar Panic
 * LICENSE: Apache 2.0
 *
 **/

namespace ArekX\JsonQL\Helpers;

class Value
{
    public static function merge()
    {
        $configs = func_get_args();

        $result = [];

        foreach ($configs as $config) {
            foreach ($config as $key => $value) {
                if (is_array($value)) {
                    $result[$key] = static::merge($result[$key] ?? [], $value);
                } else {
                    $result[$key] = $value;
                }
            }
        }

        return $result;
    }

    public static function get($object, $name, $default = null)
    {
        if (strpos($name, '.') === -1 || static::has($object, $name)) {
            return static::resolveValue($object, $name, $default);
        }

        $parts = explode('.', $name);
        $walker = &$object;

        $lastPart = array_pop($parts);

        foreach ($parts as $part) {
            if (is_array($walker) && array_key_exists($part, $walker)) {
                $walker = &$walker[$part];
            } elseif (is_object($walker) && property_exists($walker, $part)) {
                $walker = &$walker->{$part};
            } else {
                return $default;
            }
        }

        return static::resolveValue($walker, $lastPart, $default);
    }

    public static function has($object, $name)
    {
        return
            (is_array($object) && array_key_exists($name, $object)) ||
            (is_object($object) && property_exists($object, $name));
    }

    public static function setup($object, $config, $defaultConfig)
    {
        foreach ($defaultConfig as $key => $defaultValue) {
            $object->{$key} = $config[$key] ?? $defaultValue;
        }
    }

    public static function isEmpty($value, $strict = true)
    {
        if ($strict) {
            return $value === "" ||
                $value === null ||
                $value === 0 ||
                $value === 0.00;
        }

        return empty($value);
    }

    protected static function resolveValue($object, $name, $default = null)
    {
        if (is_array($object) && array_key_exists($name, $object)) {
            return $object[$name];
        }

        if (is_object($object) && property_exists($object, $name)) {
            return $object->{$name};
        }

        return $default;
    }
}