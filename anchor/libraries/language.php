<?php

/**
 * i18n class
 */
class Language {

	/**
	 * Holds array of translations
	 *
	 * @var array
	 */
	private static $lines = array();

	/**
	 * Returns the path to requested translation file
	 *
	 * @param string
	 * @return string
	 */
	private static function path($file) {
		$language = Config::app('language', 'en_GB');

		return APP . 'language/' . $language . '/' . $file . '.php';
	}

	/**
	 * Loads a translation file
	 *
	 * @param string
	 */
	private static function load($file) {
		if(is_readable($lang = static::path($file))) {
			static::$lines[$file] = require $lang;
		}
	}

	/**
	 * Replace sprintf tokens with MessageFormatter patterns
	 *
	 * @param string
	 * @return string
	 */
	private static function replace($text) {
		$old = '%s';
		$index = 0;

		while(strpos($text, $old) !== false) {
			$text = str_replace($old, '{' . $index++ . '}', $text);
		}

		return $text;
	}

	/**
	 * Reads a translation from the loaded array
	 *
	 * @param string
	 * @param string
	 * @param array
	 * @return string
	 */
	public static function line($key, $default = '', $args = array()) {
		// the first part of the key refers to the file
		$parts = explode('.', $key);

		if(count($parts) > 1) {
			$file = array_shift($parts);
			$line = array_shift($parts);
		}

		// no file specified use the global file
		if(count($parts) == 1) {
			$file = 'global';
			$line = array_shift($parts);
		}

		// load the translations
		if( ! isset(static::$lines[$file])) {
			static::load($file);
		}

		// try and match the key
		if(isset(static::$lines[$file][$line])) {
			$text = static::$lines[$file][$line];
		}
		// use the default
		else if($default) {
			$text = $default;
		}
		// use the key as default so the user can see which translation
		// key is missing
		else {
			$text = $key;
		}

		// replace tokens with $args variables
		if(count($args)) {
			// remove old tokens from sprintf formatting
			$text = static::replace($text);

			return MessageFormatter::formatMessage(Locale::getDefault(), $text, $args);
		}

		return $text;
	}

}