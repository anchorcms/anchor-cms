<?php

class Str {

	public static function truncate($str, $limit = 10, $elipse = '&hellip;') {
		$words = preg_split('/\b/', $str);

		if(count($words) <= $limit) {
			return $str;
		}

		return implode(' ', array_slice($words, 0, $limit)) . $elipse;
	}

	public static function ascii($value) {
		$foreign = Config::strings('foreign_characters');

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