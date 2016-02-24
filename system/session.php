<?php

namespace System;

class Session {

	public static function setOptions(array $options = array()) {
		foreach($options as $key => $value) {
			ini_set(sprintf('session.%s', $key), $value);
		}
	}

	public static function start() {
		session_start();
	}

	public static function close() {
		session_write_close();
	}

	public static function regenerate($destroy = false) {
		session_regenerate_id($destroy);
	}

	public static function get($key, $default = null) {
		return Arr::get($_SESSION, $key, $default);
	}

	public static function put($key, $value) {
		Arr::set($_SESSION, $key, $value);
	}

	public static function erase($key) {
		Arr::erase($_SESSION, $key);
	}

	public static function flash($data = null) {
		if(is_null($data)) {
			return static::get('_out', array());
		}

		static::put('_in', $data);
	}
}