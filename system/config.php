<?php

namespace System;

/**
 * Nano
 * Just another php framework
 *
 * @package    nano
 * @link       http://madebykieron.co.uk
 * @copyright  http://unlicense.org/
 */

/**
 * config class
 * @method static aliases(string $key, mixed $fallback = null): mixed
 * @method static app(string $key, mixed $fallback = null): mixed
 * @method static database(string $key, mixed $fallback = null): mixed
 * @method static db(string $key, mixed $fallback = null): mixed
 * @method static error(string $key, mixed $fallback = null): mixed
 * @method static session(string $key, mixed $fallback = null): mixed
 * @method static strings(string $key, mixed $fallback = null): mixed
 * @method static meta(string $key, mixed $fallback = null): mixed
 * @method static migrations(string $key, mixed $fallback = null): mixed
 *
 * @package System
 */
class config
{

    /**
     * Holds the app data
     *
     * @var array
     */
    public static $array = [];

    /**
     * Sets a value in the config array
     *
     * @param string $key   key name to set
     * @param mixed  $value key value to set
     *
     * @return void
     */
    public static function set($key, $value)
    {
        Arr::set(static::$array, $key, $value);
    }

    /**
     * Removes value in the config array
     *
     * @param string $key key name to erase
     *
     * @return void
     */
    public static function erase($key)
    {
        Arr::erase(static::$array, $key);
    }

    /**
     * Returns a value from the config array using the
     * method call as the file reference
     *
     * @example Config::app('url');
     *
     * @param string $method    original method name
     * @param array  $arguments arguments passed to the method
     *
     * @return mixed result of the get method
     */
    public static function __callStatic($method, $arguments)
    {
        $key      = $method;
        $fallback = null;

        if (count($arguments)) {
            $key      .= '.' . array_shift($arguments);
            $fallback = array_shift($arguments);
        }

        return static::get($key, $fallback);
    }

    /**
     * Returns a value from the config array
     *
     * @param string     $key      key name to get
     * @param mixed|null $fallback (optional) fallback if the key is missing
     *
     * @return mixed|null key value, fallback or null
     */
    public static function get($key, $fallback = null)
    {
        // first segment refers to config file
        $keys = explode('.', $key);

        // read the config file if we have one
        if ( ! array_key_exists($file = current($keys), static::$array)) {

            // use env config if available
            if (
                constant('ENV') and
                is_readable($path = APP . 'config' . DS . ENV . DS . $file . EXT)
            ) {
                /** @noinspection PhpIncludeInspection */
                static::$array[$file] = require $path;
            } elseif (is_readable($path = APP . 'config' . DS . $file . EXT)) {

                // is the file readable
                /** @noinspection PhpIncludeInspection */
                static::$array[$file] = require $path;
            }
        }

        return Arr::get(static::$array, $key, $fallback);
    }
}
