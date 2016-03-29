<?php

class Slugify {

	/**
	 * Format a string to be a valid uri string
	 */
	public function slug($string, $separator = '-') {
		// convert entities
		$string = html_entity_decode($string, ENT_QUOTES, 'UTF-8');

		// replace non letters
		$string = preg_replace('#[^\w\d\s]+#u', ' ', $string);

		// replace spaces and separators
		$string = preg_replace('#['.$separator.'\s]+#', $separator, $string);

		// trim spaces and separators
		$string = trim($string, $separator . " \t\n\r\0\x0B");

		// lower case
		$string = mb_strtolower($string);

		return $string;
	}

}
