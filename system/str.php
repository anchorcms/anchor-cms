<?php namespace System;

define('MB_STRING', function_exists('mb_get_info'));

class Str {

	public static function encoding() {
		return Config::get('application.encoding');
	}

	public static function entities($value) {
		return htmlentities($value, ENT_QUOTES, static::encoding(), false);
	}

	public static function lower($value) {
		return MB_STRING ? mb_strtolower($value, static::encoding()) : strtolower($value);
	}

	public static function upper($value) {
		return MB_STRING ? mb_strtoupper($value, static::encoding()) : strtoupper($value);
	}

	public static function length($value) {
		return MB_STRING ? mb_strlen($value, static::encoding()) : strlen($value);
	}

	public static function title($value) {
		return MB_STRING ? mb_convert_case($value, MB_CASE_TITLE, static::encoding()) : ucwords(strtolower($value));
	}

	public static function random($length = 16) {
		$pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

		return substr(str_shuffle(str_repeat($pool, 5)), 0, $length);
	}

	public static function truncate($str, $limit = 10, $elipse = ' [...]') {
		$words = preg_split('/\s+/', $str);

		if(count($words) <= $limit) {
			return $str;
		}

		return implode(' ', array_slice($words, 0, $limit)) . $elipse;
	}

	public static function ascii($value) {
		$foreign = Config::get('strings.foreign_characters');

		$value = preg_replace(array_keys($foreign), array_values($foreign), $value);

		return preg_replace('/[^\x09\x0A\x0D\x20-\x7E]/', '', $value);
	}

	public static function slug($title) {
		$title = static::ascii($title);
		$separator = '-';

		// Remove all characters that are not the separator, letters, numbers, or whitespace.
		$title = preg_replace('![^'.preg_quote($separator).'\pL\pN\s]+!u', '', static::lower($title));

		// Replace all separator characters and whitespace by a single separator
		$title = preg_replace('!['.preg_quote($separator).'\s]+!u', $separator, $title);

		return trim($title, $separator);
	}

}