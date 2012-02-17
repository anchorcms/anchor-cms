<?php defined('IN_CMS') or die('No direct access allowed.');

/*
	Helper for setting php settings
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