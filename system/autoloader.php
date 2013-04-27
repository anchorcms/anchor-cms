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

class Autoloader {

	/**
	 * Hold an array of directories to search
	 *
	 * @var array
	 */
	public static $directories = array();

	/**
	 * Hold an array of class aliases
	 *
	 * @var array
	 */
	public static $aliases = array();

	/**
	 * Append a path to the array of directories to search
	 *
	 * @param string
	 */
	public static function directory($paths) {
		if( ! is_array($paths)) $paths = array($paths);

		foreach($paths as $path) {
			static::$directories[] = rtrim($path, DS) . DS;
		}
	}

	/**
	 * Attempts to load a class
	 *
	 * @link https://github.com/php-fig/fig-standards
	 * @param string
	 */
	public static function load($class) {
		$file = str_replace(array('\\', '_'), DS, ltrim($class, '\\'));
		$lower = strtolower($file);

		if(array_key_exists(strtolower($class), array_change_key_case(static::$aliases))) {
			return class_alias(static::$aliases[$class], $class);
		}

		foreach(static::$directories as $directory) {
			if(is_readable($path = $directory . $lower . EXT)) {
				return require $path;
			}
			else if(is_readable($path = $directory . $file . EXT)) {
				return require $path;
			}
		}

		return false;
	}

}