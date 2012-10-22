<?php namespace System;

class Input {

	public static $array = array();

	public static function get($key, $default = null) {
		if(array_key_exists($key, static::$array)) {
			return static::$array[$key];
		}

		return $default;
	}

	public static function get_array($arr, $default = null) {
		$input = array();

		foreach($arr as $key) {
			$input[$key] = static::get($key, $default);
		}

		return $input;
	}

	public static function old($key, $default = null) {
		$array = Session::get(':old:');

		if(array_key_exists($key, $array)) {
			return $array[$key];
		}

		return $default;
	}

	public static function flash() {
		Session::put(':new:', static::$array);
	}

	public static function filter($key, $default = null) {
		return filter_var(static::get($key), FILTER_SANITIZE_STRING);
	}

}
