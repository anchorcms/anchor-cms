<?php defined('IN_CMS') or die('No direct access allowed.');

class Autoloader {

	private static $mappings = array();
	private static $directories = array();

	public static function register() {
		spl_autoload_register(array('Autoloader', 'load'));
	}
	
	public static function unregister() {
		spl_autoload_unregister(array('Autoloader', 'load'));
	}

	public static function map($map) {
		static::$mappings = array_merge(static::$mappings, $map);
	}

	public static function directory($dir) {
		static::$directories = array_merge(static::$directories, $dir);
	}
	
	public static function load($class) {
		// does the class have a direct map
		if(isset(static::$mappings[$class])) {
			// load class
			require static::$mappings[$class];

			return true;
		}

		// search directories
		$file = str_replace(array('//', '\\'), '/', trim(strtolower($class), '/'));

		// get file path
		if(($path = static::find($file)) === false) {
			return false;
		}

		require $path;

		return true;
	}
	
	public static function find($file) {
		// search controllers
		if(strpos($file, '_controller') !== false) {
			$file = rtrim($file, '_controller');
			$path = PATH . 'system/admin/controllers/';

			if(file_exists($path . $file . '.php')) {
				return $path . $file . '.php';
			}
		}

		// search application classes
		foreach(static::$directories as $path) {
			if(file_exists($path . $file . '.php')) {
				return $path . $file . '.php';
			}
		}
		
		return false;
	}

}
