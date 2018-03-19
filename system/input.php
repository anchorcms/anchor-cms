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
 * input class
 *
 * @package System
 */
class input
{
    /**
     * Array or request vars
     *
     * @var array
     */
    public static $array;

    /**
     * Try and collect the request input determined by the request method
     *
     * @param string $method HTTP method to detect input for.
     *                       Currently supports GET and POST
     *
     * @return void
     */
    public static function detect($method)
    {
        switch ($method) {
            case 'GET':
                $query = parse_url(Arr::get($_SERVER, 'REQUEST_URI'), PHP_URL_QUERY);
                parse_str($query, static::$array);
                break;

            case 'POST':
                static::$array = $_POST;
                break;

            default:
                parse_str(file_get_contents('php://input'), static::$array);
        }
    }

    /**
     * Get a element or array of elements from the input array
     *
     * @param string|string[] $key      name of a key or an array of keys to retrieve
     * @param mixed           $fallback (optional)
     *
     * @return mixed
     */
    public static function get($key, $fallback = null)
    {
        if (is_array($key)) {
            return static::get_array($key, $fallback);
        }

        return Arr::get(static::$array, $key, $fallback);
    }

    /**
     * Get a array of elements from the input array
     *
     * @param string[] $array    array of keys to retrieve
     * @param mixed    $fallback (optional) fallback value
     *
     * @return array key-value list
     */
    public static function get_array($array, $fallback = null)
    {
        $values = [];

        foreach ($array as $key) {
            $values[$key] = static::get($key, $fallback);
        }

        return $values;
    }

    /**
     * Save the input array for the next request
     *
     * @return void
     */
    public static function flash()
    {
        Session::flash(static::$array);
    }

    /**
     * Get a element from the previous request input array
     *
     * @param string $key      name of a key from the previous request input
     * @param mixed  $fallback fallback value
     *
     * @return mixed
     */
    public static function previous($key, $fallback = null)
    {
        return Arr::get(Session::flash(), $key, $fallback);
    }
}
