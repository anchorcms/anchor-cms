<?php

class Language {

	private static $lines = array();

	private static function path($file) {
		$language = Config::get('application.language');

		return APP . 'language/' . $language . '/' . $file . '.php';
	}

	private static function load($file) {
		if(is_readable($lang = static::path($file))) {
			static::$lines[$file] = require $lang;
		}
	}

	public static function line($key, $default = '') {
		list($file, $line) = explode('.', $key);

		if( ! isset(static::$lines[$file])) static::load($file);

		if(isset(static::$lines[$file][$line])) {
			return static::$lines[$file][$line];
		}

		return $default;
	}

}