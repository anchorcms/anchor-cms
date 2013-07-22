<?php namespace i18n;

class Locale {

	public static $locate = 'en_US';

	public static function setDefault($lang) {
		static::$locate = $lang;
	}

	public static function getDefault() {
		return static::$locate;
	}

}