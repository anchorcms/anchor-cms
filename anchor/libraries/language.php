<?php

class Language {

	private static $lines = array();

	private static function path($file) {
		$language = Config::app('language', 'en_GB');

		return APP . 'language/' . $language . '/' . $file . '.php';
	}

	private static function load($file) {
		if(is_readable($lang = static::path($file))) {
			static::$lines[$file] = require $lang;
		}
	}

	public static function line($key, $default = '', $args = array()) {
		$parts = explode('.', $key);

		if(count($parts) > 1) {
			$file = array_shift($parts);
			$line = array_shift($parts);
		}

		if(count($parts) == 1) {
			$file = 'global';
			$line = array_shift($parts);
		}

		if( ! isset(static::$lines[$file])) {
			static::load($file);
		}

		if(isset(static::$lines[$file][$line])) {
			$text = static::$lines[$file][$line];
		}
		else if($default) {
			$text = $default;
		}
		else {
			$text = $key;
		}

		if(count($args)) {
			return call_user_func_array('sprintf', array_merge(array($text), $args));
		}

		return $text;
	}

}