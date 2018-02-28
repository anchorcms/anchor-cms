<?php

/**
 * registry class
 * Central data store for AnchorCMS. Almost all template functions rely on this
 */
class registry
{

    /**
     * Holds the registry data
     *
     * @var array
     */
    private static $data = [];

    /**
     * Retrieves a sub-key from the registry
     *
     * @param string $object  name of an object to retrieve a key from
     * @param bool   $key     (optional) key to retrieve from the object
     * @param null   $default (optional) default value for missing objects or keys
     *
     * @return mixed|null
     */
    public static function prop($object, $key = false, $default = null)
    {
        if ($obj = static::get($object)) {
            return ($key != false ? $obj->{$key} : $obj);
        }

        return $default;
    }

    /**
     * Retrieves a key from the registry
     *
     * @param string $key     name of the key to retrieve
     * @param null   $default (optional) fallback value for missing keys
     *
     * @return mixed|null key value if found, fallback if given, or null otherwise
     */
    public static function get($key, $default = null)
    {
        if (isset(static::$data[$key])) {
            return static::$data[$key];
        }

        return $default;
    }

    /**
     * Sets a value to the registry
     *
     * @param string $key   name of the key to set
     * @param mixed  $value value to set
     *
     * @return void
     */
    public static function set($key, $value)
    {
        static::$data[$key] = $value;
    }

    /**
     * Checks whether the registry has a key
     *
     * @param string $key name of the key to check for
     *
     * @return bool
     */
    public static function has($key)
    {
        return isset(static::$data[$key]);
    }
}
