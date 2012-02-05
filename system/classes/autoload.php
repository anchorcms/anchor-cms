<?php defined('IN_CMS') or die('No direct access allowed.');

class Autoloader {

	public static function register() {
		spl_autoload_register(array('Autoloader', 'load'));
	}
	
	public static function unregister() {
		spl_autoload_unregister(array('Autoloader', 'load'));
	}
	
	public static function load($class) {
		$file = str_replace(array('//', '\\'), '/', trim(strtolower($class), '/'));

		// get file path
		if(($path = static::find($file)) === false) {
			return false;
		}

		require $path;

		return true;
	}
	
	public static function find($file) {
		// search system and application paths
		foreach(array(PATH . 'system/classes/') as $path) {
			if(stream_resolve_include_path($path . $file . '.php')) {
				return $path . $file . '.php';
			}
		}
		
		return false;
	}

}
