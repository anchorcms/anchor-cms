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

class Config {

	/**
	 * Holds the app data
	 *
	 * @var array
	 */
	public static $array = array();

	/**
	 * Returns a value from the config array
	 *
	 * @param string
	 * @param mixed
	 * @return mixed
	 */
	public static function get($key, $fallback = null) {
		// first segment refers to config file
		$keys = explode('.', $key);

		// read the config file if we have one
		if( ! array_key_exists($file = current($keys), static::$array)) {
			// use env config if available
			if(constant('ENV') and is_readable($path = APP . 'config' . DS . ENV . DS . $file . EXT)) {
				static::$array[$file] = require $path;
			}

			// is the file readable
			elseif(is_readable($path = APP . 'config' . DS . $file . EXT)) {
				static::$array[$file] = require $path;
			}
		}

		return Arr::get(static::$array, $key, $fallback);
	}

	/**
	 * Sets a value in the config array
	 *
	 * @param string
	 * @param mixed
	 */
	public static function set($key, $value) {
		Arr::set(static::$array, $key, $value);
	}

	/**
	 * Removes value in the config array
	 *
	 * @param string
	 */
	public static function erase($key) {
		Arr::erase(static::$array, $key);
	}

	/**
	 * Returns a value from the config array using the
	 * method call as the file reference
	 *
	 * @example Config::app('url');
	 *
	 * @param string
	 * @param array
	 */
	public static function __callStatic($method, $arguments) {
		$key = $method;
		$fallback = null;

		if(count($arguments)) {
			$key .= '.' . array_shift($arguments);
			$fallback = array_shift($arguments);
		}

		return static::get($key, $fallback);
	}

}