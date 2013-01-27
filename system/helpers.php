<?php

/*
	Array helpers
*/

// Get an item from an array using "dot" notation.
function array_get($array, $key, $default = null) {
	if(is_null($key)) return $array;

	foreach(explode('.', $key) as $segment) {
		if(!is_array($array) or !array_key_exists($segment, $array)) {
			return value($default);
		}

		$array = $array[$segment];
	}

	return $array;
}

// Set an array item to a given value using "dot" notation.
function array_set(&$array, $key, $value) {
	if (is_null($key)) return $array = $value;

	$keys = explode('.', $key);

	while(count($keys) > 1) {
		$key = array_shift($keys);

		if(!isset($array[$key]) or !is_array($array[$key])) {
			$array[$key] = array();
		}

		$array =& $array[$key];
	}

	$array[array_shift($keys)] = $value;
}

// Remove an array item from a given array using "dot" notation.
function array_forget(&$array, $key) {
	$keys = explode('.', $key);

	while(count($keys) > 1) {
		$key = array_shift($keys);

		if( ! isset($array[$key]) or ! is_array($array[$key])) {
			return;
		}

		$array =& $array[$key];
	}

	unset($array[array_shift($keys)]);
}

// Divide an array into two arrays. One with keys and the other with values.
function array_divide($array) {
	return array(array_keys($array), array_values($array));
}

// Recursively remove slashes from array keys and values.
function array_strip_slashes($array) {
	$result = array();

	foreach($array as $key => $value) {
		$key = stripslashes($key);

		if(is_array($value)) {
			$result[$key] = array_strip_slashes($value);
		}
		else {
			$result[$key] = stripslashes($value);
		}
	}

	return $result;
}

/*
	String Helpers
*/

// Cap a string with a single instance of the given string.
function str_finish($value, $cap) {
	return rtrim($value, $cap) . $cap;
}

// Determine if a given string contains a given sub-string.
function str_contains($haystack, $needle) {
	return strpos($haystack, $needle) !== false;
}

// Determine if a given string begins with a given value.
function starts_with($haystack, $needle) {
	return strpos($haystack, $needle) === 0;
}

// Determine if a given string ends with a given value.
function ends_with($haystack, $needle) {
	return $needle == substr($haystack, strlen($haystack) - strlen($needle));
}

/*
	View helpers
*/

// Generate an application URL to an asset.
function asset($uri) {
	return str_finish(System\Config::get('application.url'), '/') . $uri;
}

// Generate an application URL.
function url($url = '') {
	return System\Uri::make($url);
}

// Convert HTML characters to entities.
function e($value) {
	return System\Html::entities($value);
}

// Render the given view.
function render($view, $data = array()) {
	if (is_null($view)) return '';

	return System\View::make($view, $data)->render();
}

/*
	Misc
*/

// Determine if "Magic Quotes" are enabled on the server.
function magic_quotes() {
	return function_exists('get_magic_quotes_gpc') and get_magic_quotes_gpc();
}

// Data dump
function dd() {
	echo '<pre>';
	call_user_func_array('var_dump', func_get_args());
	echo '</pre>';
	exit;
}

// Determine if the current version of PHP is at least the supplied version.
function has_php($version) {
	return version_compare(PHP_VERSION, $version) >= 0;
}

// Return the value of the given item.
function value($value) {
	return (is_callable($value) and ! is_string($value)) ? call_user_func($value) : $value;
}

// Calculate the human-readable file size (with proper units).
function get_file_size($size) {
	$units = array('Bytes', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB');

	return round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . ' ' . $units[$i];
}