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
 * arr class
 * Provides a convenient Array helper
 *
 * @package System
 */
class arr
{
    /**
     * Holds the stack instances operate on
     *
     * @var array
     */
    protected $stack;

    /**
     * Arr constructor
     *
     * @param array $stack (optional) array to create an instance from
     */
    public function __construct($stack = [])
    {
        $this->stack = $stack;
    }

    /**
     * Return a element from a array
     *
     * @param array      $array    array to retrieve a value from
     * @param string|int $key      name of the key to retrieve
     * @param mixed|null $fallback (optional) fallback value to return if the requested key
     *                             cannot be found
     *
     * @return mixed|null key in the array, fallback if not found or null if no fallback given
     */
    public static function get($array, $key, $fallback = null)
    {
        // search the array using the dot character to access nested array values
        foreach ($keys = explode('.', $key) as $key) {
            // when a key is not found or we didnt get an array to search return a fallback value
            if ( ! is_array($array) or ! array_key_exists($key, $array)) {
                return $fallback;
            }

            $array =& $array[$key];
        }

        return $array;
    }

    /**
     * Sets a value in a array
     *
     * @param array      $array array to set a value in
     * @param string|int $key   name of the key to set
     * @param mixed      $value value to set
     *
     * @return void
     */
    public static function set(&$array, $key, $value)
    {
        $keys = explode('.', $key);

        // traverse the array into the second last key
        while (count($keys) > 1) {
            $key = array_shift($keys);

            // make sure we have a array to set our new key in
            if ( ! array_key_exists($key, $array)) {
                $array[$key] = [];
            }

            $array =& $array[$key];
        }

        $array[array_shift($keys)] = $value;
    }

    /**
     * Remove a value from a array
     *
     * @param array      $array array to remove a value from
     * @param string|int $key   name of the key to remove
     *
     * @return void
     */
    public static function erase(&$array, $key)
    {
        $keys = explode('.', $key);

        // traverse the array into the second last key
        while (count($keys) > 1) {
            $key = array_shift($keys);

            if (array_key_exists($key, $array)) {
                $array =& $array[$key];
            }
        }

        // if the last key exists unset it
        if (array_key_exists($key = array_shift($keys), $array)) {
            unset($array[$key]);
        }
    }

    /**
     * Create a new instance of the Arr class
     *
     * @param array $stack (optional) array to create an instance from
     *
     * @return \System\arr
     */
    public static function create($stack = [])
    {
        return new static($stack);
    }

    /**
     * Shuffle the array elements in the stack
     *
     * @return \System\arr self for chaining
     */
    public function shuffle()
    {
        shuffle($this->stack);

        return $this;
    }

    /**
     * Returns the first element in the stack
     *
     * @return mixed
     */
    public function first()
    {
        return current($this->stack);
    }

    /**
     * Returns the last element in the stack
     *
     * @return mixed
     */
    public function last()
    {
        return end($this->stack);
    }
}
