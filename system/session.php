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
 * session class
 *
 * @package System
 */
class session
{
    /**
     * Sets PHP.ini session settings
     *
     * @param array $options (optional) session options to set
     *
     * @return void
     */
    public static function setOptions(array $options = [])
    {
        foreach ($options as $key => $value) {
            ini_set(sprintf('session.%s', $key), $value);
        }
    }

    /**
     * Starts a new session
     *
     * @return void
     */
    public static function start()
    {
        session_start();
    }

    /**
     * Closes the current session
     *
     * @return void
     */
    public static function close()
    {
        session_write_close();
    }

    /**
     * Regenerates the session ID. By passing destroy, the session data will be destroyed
     *
     * @param bool $destroy whether to destroy the session data
     *
     * @return void
     */
    public static function regenerate($destroy = false)
    {
        session_regenerate_id($destroy);
    }

    /**
     * Erases a key from the session data
     *
     * @param string $key name of the key to erase
     *
     * @return void
     */
    public static function erase($key)
    {
        Arr::erase($_SESSION, $key);
    }

    /**
     * Retrieves or saves data to the session to use it in the next request.
     * If no data is passed, it will return the previously flashed data, otherwise
     * store the passed data
     *
     * @param mixed|null $data (optional) data to flash to the session. Omit to
     *                         retrieve previously flashed data
     *
     * @return mixed|null null if data passed, previously flashed data otherwise
     */
    public static function flash($data = null)
    {
        if (is_null($data)) {
            return static::get('_out', []);
        }

        static::put('_in', $data);

        return null;
    }

    /**
     * Retrieves a key from the session data
     *
     * @param string $key     name of the key to retrieve
     * @param null   $default fallback value for missing keys
     *
     * @return mixed|null key data for found keys, default if passed or null otherwise
     */
    public static function get($key, $default = null)
    {
        return Arr::get($_SESSION, $key, $default);
    }

    /**
     * Sets a key to the session data. Overwrites existing keys
     *
     * @param string $key   name of the key to set
     * @param mixed  $value value to set the key to
     *
     * @return void
     */
    public static function put($key, $value)
    {
        Arr::set($_SESSION, $key, $value);
    }
}
