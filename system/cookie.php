<?php namespace System;

/**
 * Nano
 *
 * Just another php framework
 *
 * @package		nano
 * @link		http://madebykieron.co.uk
 * @copyright	http://unlicense.org/
 */

class Cookie {

	/**
	 * Array of cookie to be written by the response class
	 *
	 * @var array
	 */
	public static $bag = array();

	/**
	 * Adds a cookie to the bag to be written
	 *
	 * @param string
	 * @param mixed
	 * @param int
	 * @param string
	 * @param string
	 * @param bool
	 */
	public static function write($name, $value, $expiration = 0, $path = '/', $domain = null, $secure = false, $HttpOnly = true) {
		if($expiration !== 0) $expiration = time() + $expiration;

		static::$bag[$name] = compact('name', 'value', 'expiration', 'path', 'domain', 'secure', 'HttpOnly');
	}

	/**
	 * Reads a cookie name from the globals cookies or the class cookie bag
	 *
	 * @param string
	 * @param mixed
	 * @return mixed
	 */
	public static function read($name, $fallback = null) {
		if(array_key_exists($name, static::$bag)) return static::$bag[$name]['value'];

		return array_key_exists($name, $_COOKIE) ? $_COOKIE[$name] : $fallback;
	}

	/**
	 * Remove a cookie from the bag
	 *
	 * @param string
	 * @param string
	 * @param string
	 * @param bool
	 */
	public static function erase($name, $path = '/', $domain = null, $secure = false) {
		static::write($name, null, -2000, $path, $domain, $secure);
	}

}
