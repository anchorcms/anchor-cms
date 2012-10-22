<?php

/**
 * Get an item from an array using "dot" notation.
 */
function array_get($array, $key, $default = null) {
	if(is_null($key)) return $array;

	// To retrieve the array item using dot syntax, we'll iterate through
	// each segment in the key and look for that value. If it exists, we
	// will return it, otherwise we will set the depth of the array and
	// look for the next segment.
	foreach(explode('.', $key) as $segment) {
		if(!is_array($array) or !array_key_exists($segment, $array)) {
			return value($default);
		}

		$array = $array[$segment];
	}

	return $array;
}

/**
 * Set an array item to a given value using "dot" notation.
 */
function array_set(&$array, $key, $value) {

	if (is_null($key)) return $array = $value;

	$keys = explode('.', $key);

	// This loop allows us to dig down into the array to a dynamic depth by
	// setting the array value for each level that we dig into. Once there
	// is one key left, we can fall out of the loop and set the value as
	// we should be at the proper depth.
	while(count($keys) > 1) {
		$key = array_shift($keys);

		// If the key doesn't exist at this depth, we will just create an
		// empty array to hold the next value, allowing us to create the
		// arrays to hold the final value.
		if(!isset($array[$key]) or !is_array($array[$key])) {
			$array[$key] = array();
		}

		$array =& $array[$key];
	}

	$array[array_shift($keys)] = $value;
}

/**
 * Remove an array item from a given array using "dot" notation.
 */
function array_forget(&$array, $key) {
	$keys = explode('.', $key);

	// This loop functions very similarly to the loop in the "set" method.
	// We will iterate over the keys, setting the array value to the new
	// depth at each iteration. Once there is only one key left, we will
	// be at the proper depth in the array.
	while(count($keys) > 1) {
		$key = array_shift($keys);

		// Since this method is supposed to remove a value from the array,
		// if a value higher up in the chain doesn't exist, there is no
		// need to keep digging into the array, since it is impossible
		// for the final value to even exist.
		if(!isset($array[$key]) or !is_array($array[$key])) {
			return;
		}

		$array =& $array[$key];
	}

	unset($array[array_shift($keys)]);
}

/**
 * Recursively remove slashes from array keys and values.
 */
function array_strip_slashes($array) {
	$result = array();

	foreach($array as $key => $value) {
		$key = stripslashes($key);

		// If the value is an array, we will just recurse back into the
		// function to keep stripping the slashes out of the array,
		// otherwise we will set the stripped value.
		if(is_array($value)) {
			$result[$key] = array_strip_slashes($value);
		}
		else {
			$result[$key] = stripslashes($value);
		}
	}

	return $result;
}

/**
 * Determine if "Magic Quotes" are enabled on the server.
 */
function magic_quotes() {
	return function_exists('get_magic_quotes_gpc') and get_magic_quotes_gpc();
}

/**
 * Return the value of the given item.
 *
 * If the given item is a Closure the result of the Closure will be returned.
 */
function value($value) {
	return ($value instanceof Closure) ? call_user_func($value) : $value;
}

/**
 * Cap a string with a single instance of the given string.
 */
function str_finish($value, $cap) {
	return rtrim($value, $cap).$cap;
}

/**
 * Data dump
 */
function dd() {
	call_user_func_array('var_dump', func_get_args());

	exit;
}

/**
 * Determine if the current version of PHP is at least the supplied version.
 */
function has_php($version) {
	return version_compare(PHP_VERSION, $version) >= 0;
}