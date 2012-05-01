<?php defined('IN_CMS') or die('No direct access allowed.');

/*
	Compatibility helpers for various hosts
*/
function ini_safe_set($key, $value) {
	// some hosts disable ini_set for security 
	// lets check so see if its disabled
	if(($disable_functions = ini_get('disable_functions')) !== false) {
		// if it is disabled then return as there is nothing we can do
		if(strpos($disable_functions, 'ini_set') !== false) {
			return false;
		}
	}

	// lets set it because we can!
	return ini_set($key, $value);
}

function file_safe_exists($path) {
	// When open_basedir restriction are in effect
	// check the file is in the same path.
	if(ini_get('open_basedir')) {
		if(strpos($path, PATH) !== 0) {
			return false;
		}
	}

	return file_exists($path);
}

function array_strip_slashes($array) {
	$result = array();

	foreach($array as $key => $value) {
		$key = stripslashes($key);

		// If the value is an array, we will just recurse back into the
		// function to keep stripping the slashes out of the array,
		// otherwise we will set the stripped value.
		if (is_array($value)) {
			$result[$key] = array_strip_slashes($value);
		} else {
			$result[$key] = stripslashes($value);
		}
	}

	return $result;
}

function magic_quotes() {
	// Determine if "Magic Quotes" are enabled on the server.
	return function_exists('get_magic_quotes_gpc') and get_magic_quotes_gpc();
}

function has_php($version) {
	return version_compare(PHP_VERSION, $version) >= 0;
}