<?php namespace i18n;

class Locale {

	public static $locate = 'en_US';

	public static function setDefault($lang) {
		static::$locate = $lang;
	}

	public static function getDefault() {
		return static::$locate;
	}

	/**
	 * prototype: en-GB,en-US;q=0.8,en;q=0.6
	 */
	public static function acceptFromHttp($header) {
		$parts = explode(';', $header);
		$langs = explode(',', $parts[0]);

		if(count($langs)) {
			return str_replace('-', '_', $langs[0]);
		}
	}

}