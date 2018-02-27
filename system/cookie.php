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
 * cookie class
 *
 * @package System
 */
class cookie
{

    /**
     * Array of cookie to be written by the response class
     *
     * @var array
     */
    public static $bag = [];

    /**
     * Reads a cookie name from the globals cookies or the class cookie bag
     *
     * @param string     $name     name of the cookie to read
     * @param mixed|null $fallback (optional) fallback if the cookie is missing
     *
     * @return string|null
     */
    public static function read($name, $fallback = null)
    {
        if (array_key_exists($name, static::$bag)) {
            return static::$bag[$name]['value'];
        }

        return array_key_exists($name, $_COOKIE) ? $_COOKIE[$name] : $fallback;
    }

    /**
     * Remove a cookie from the bag
     *
     * @param string      $name   name of the cookie to erase
     * @param string      $path   path for the cookie to set
     * @param string|null $domain domain for the cooke to set
     * @param bool        $secure whether the cookie should be set to secure
     *
     * @return void
     */
    public static function erase($name, $path = '/', $domain = null, $secure = false)
    {
        static::write($name, null, -2000, $path, $domain, $secure);
    }

    /**
     * Adds a cookie to the bag to be written
     *
     * @param string $name       name for the cookie
     * @param string $value      value for the cookie
     * @param int    $expiration expiration date
     * @param string $path       cookie path
     * @param null   $domain     cookie domain scope
     * @param bool   $secure     whether to set the cookie as secure
     * @param bool   $HttpOnly   whether to set the cookie as http only
     *
     * @return void
     */
    public static function write(
        $name,
        $value,
        $expiration = 0,
        $path = '/',
        $domain = null,
        $secure = false,
        $HttpOnly = true
    ) {
        if ($expiration !== 0) {
            $expiration = time() + $expiration;
        }

        static::$bag[$name] = compact('name', 'value', 'expiration', 'path', 'domain', 'secure', 'HttpOnly');
    }
}
