<?php defined('IN_CMS') or die('No direct access allowed.');

/*
	A static config class to manage all of 
	our config params
*/
class Config {

	private static $items = array();

	/*
		Load the default config file
	*/
	public static function load() {
		if(file_exists(PATH . 'config.php') === false) {
			return false;
		}
		
		static::$items = require PATH . 'config.php';
		
		return true;
	}
	
	/*
		Set a config item
	*/
	public static function set($key, $value) {
		static::$items[$key] = $value;
	}
	
	/*
		Retrive a config param
	*/
	public static function get($key, $default = false) {
		$parts = explode('.', $key);
		$items = static::$items;
		$index = 0;
		
		while(isset($items[$parts[$index]])) {
			$items = $items[$parts[$index]];
			$index++;

			if(!isset($parts[$index])) {
				return $items;
			}
		}

		return $default;
	}
}
