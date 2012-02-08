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
		// search controllers
		if(strpos($file, '_controller') !== false) {	
			$path = PATH . 'system/admin/controllers/';
			$controller = rtrim($file, '_controller');
			
			if(file_exists($path . $controller . '.php')) {
				return $path . $controller . '.php';
			}
		}
		
		// search application classes
		$path = PATH . 'system/classes/';
		
		if(file_exists($path . $file . '.php')) {
			return $path . $file . '.php';
		}
		
		return false;
	}

}
