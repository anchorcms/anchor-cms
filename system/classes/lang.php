<?php defined('IN_CMS') or die('No direct access allowed.');

class Lang {
	
	private static $lines = array();

	private static function load($file, $language) {
		
		if(isset(static::$lines[$language][$file])) {
			return true;
		}

		if(file_exists(static::path($file, $language)) === false) {
			return false;
		}

		static::$lines[$language][$file] = require static::path($file, $language);
	}

	private static function path($file, $language) {
		return PATH . 'system/language/' . $language . '/' . $file . '.php';
	}

	public static function line($key, $default = null) {
		// set language, if not set choose 'en'
		if (!($language = Config::get('application.language'))) $language = 'en'; // TODO: Remove this at some point, just for 0.6--> 0.7 upgrading without errors.

		// parse
		list($file, $line) = explode('.', $key);

		if(static::load($file, $language) === false) {
			return $default;
		}

		if(isset(static::$lines[$language][$file][$line])) {
			return static::$lines[$language][$file][$line];
		}

		return $default;
	}

	public static function get($file, $default = false) {
		// set language
		$language = Config::get('application.language');

		if(static::load($file, $language) === false) {
			return $default;
		}

		return static::$lines[$language][$file];
	}

}