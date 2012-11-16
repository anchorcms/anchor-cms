<?php namespace System;

/**
 * Nano
 *
 * Lightweight php framework
 *
 * @package		nano
 * @author		k. wilson
 * @link		http://madebykieron.co.uk
 */

class Autoloader {

	public static $aliases = array();

	public static $namespaces = array();

	public static $directories = array();

	public static function directories($directory) {
		$directories = static::format($directory);

		static::$directories = array_unique(array_merge(static::$directories, $directories));
	}

	protected static function format($directories) {
		return array_map(function($directory) {
			return rtrim($directory, DS) . DS;
		}, $directories);
	}

	public static function namespaces($namespaces) {
		$namespaces = static::format_mappings($namespaces, '\\');

		static::$namespaces = array_unique(array_merge(static::$namespaces, $namespaces));
	}

	protected static function format_mappings($mappings, $append) {
		foreach($mappings as $namespace => $directory) {
			// When adding new namespaces to the mappings, we will unset the previously
			// mapped value if it existed. This allows previously registered spaces to
			// be mapped to new directories on the fly.
			$namespace = trim($namespace, $append) . $append;

			unset(static::$namespaces[$namespace]);

			$namespaces[$namespace] = current(static::format(array($directory)));
		}

		return $namespaces;
	}

	public static function alias($class, $alias) {
		static::$aliases[$alias] = $class;
	}

	public static function load($class) {
		if(array_key_exists(strtolower($class), array_change_key_case(static::$aliases))) {
			return class_alias(static::$aliases[$class], $class);
		}

		foreach(static::$namespaces as $namespace => $directory) {
			if(strpos($class, $namespace) === 0) {
				if($path = static::load_namespaced($class, $namespace, $directory)) {
					return require $path;
				}
			}
		}

		if($path = static::find($class)) {
			return require $path;
		}

		return false;
	}

	protected static function load_namespaced($class, $namespace, $directory) {
		return static::find(substr($class, strlen($namespace)), $directory);
	}

	public static function find($class, $directory = null) {
		// Auto controllers
		$prefix = '_controller';

		if(stripos($class, $prefix) !== false) {
			$controllers = APP_PATH . 'controllers/';
			$file = substr($class, 0, strlen($prefix) * -1);
			$lower = strtolower($file);

			if(is_readable($path = $controllers . $lower . EXT)) {
				return $path;
			}
			elseif(is_readable($path = $controllers . $file . EXT)) {
				return $path;
			}
		}

		// The PSR-0 standard indicates that class namespaces and underscores
		// should be used to indicate the directory tree in which the class
		// resides, so we'll convert them to slashes.
		$file = str_replace(array('\\', '_'), '/', $class);

		$directories = $directory ? array($directory) : static::$directories;

		$lower = strtolower($file);

		foreach($directories as $directory) {
			if(is_readable($path = $directory . $lower . EXT)) {
				return $path;
			}
			elseif(is_readable($path = $directory . $file . EXT)) {
				return $path;
			}
		}

		return false;
	}

}
