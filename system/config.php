<?php namespace System;

class Config {

	public static $items = array(),  $cache = array(), $mapped = array();
	
	public static function get($key, $default = null) {
		// return cached
		if(isset(static::$cache[$key])) {
			return static::$cache[$key];
		}

		// load config file
		$file = current(explode('.', $key));

		if(!array_key_exists($file, static::$mapped)) {
			static::load($file);
		}

		// cache it for faster lookups
		static::$cache[$key] = array_get(static::$items, $key, $default);

		return static::$cache[$key];
	}

	public static function set($key, $value) {
		array_set(static::$items, $key, $value);
	}

	public static function forget($key) {
		array_forget(static::$items, $key);
	}

	public static function load($file) {
		if(in_array($file, static::$mapped)) return;

		if(is_readable($path = APP . 'config/' . $file . '.php')) {
			// add file to mapped files
			static::$mapped[] = $file;

			// get returned array
			static::$items[$file] = require $path;
		}
	}

}