<?php namespace System;

use System\Config;

class Autoloader {

	private static $mappings = array(), $directories = array(), $aliases = array();

	public static function map($map) {
		static::$mappings = array_merge(static::$mappings, $map);
	}

	public static function directory($dir) {
		static::$directories = array_merge(static::$directories, $dir);
	}

	public static function register() {
		spl_autoload_register(array(__NAMESPACE__ .'\Autoloader', 'load'));
	}

	public static function unregister() {
		spl_autoload_unregister(array(__NAMESPACE__ .'\Autoloader', 'load'));
	}

	public static function load($class) {
		// does the class have a direct map
		if(isset(static::$mappings[$class])) {
			return require static::$mappings[$class];
		}

		// format file name
		$file = str_replace(array('//', '\\'), DS, trim(strtolower($class), DS));

		// load aliases
		if(empty(static::$aliases)) {
			static::$aliases = Config::get('aliases', array());
		}

		// find alias
		if(array_key_exists(strtolower($class), array_change_key_case(static::$aliases))) {
			return class_alias(static::$aliases[$class], $class);
		}
		
		// get file path
		if($path = static::find($file)) {
			return require $path;
		}

		return false;
	}
	
	public static function find($file) {
		// search system and application paths
		foreach(static::$directories as $path) {
			if(is_readable($path . $file . '.php')) {
				return $path . $file . '.php';
			}
		}

		return false;
	}

}
