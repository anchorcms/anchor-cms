<?php

/**
 * Shortcut to the language class line function
 * note: if a line is not found the index will be returned
 *
 * usage:
 *	<?php echo __('languagefile.index'); ?>
 *
 * @param string
 * @return string
 */
function __($message) {
	return i18n\Translation::__($message, array_slice(func_get_args(), 1));
}

/**
 * Uses the current uri to determain if we are accessing a admin route
 *
 * @return bool
 */
function is_admin() {
	return strpos(Uri::current(), 'admin') === 0;
}

/**
 * Checks if the config class has picked up a db file or for older
 * versions database.
 *
 * @return bool
 */
function is_installed() {
	return Config::get('db') !== null or Config::get('database') !== null;
}

/**
 * Converts a string into a uri slug
 *
 * @param string
 * @return string
 */
function slug($str, $separator = '-') {
	$str = normalize($str);

	// replace non letter or digits by separator
	$str = preg_replace('#^[^A-z0-9]+$#', $separator, $str);

	return trim(strtolower($str), $separator);
}

/**
 * Parses a string and replaces placeholders with data from the meta table
 * in the format of {{metakeyword}}
 *
 * @param string
 * @return string
 */
function parse($str) {
	// process tags
	$pattern = '#\{\{([A-z0-9_-]+)\}\}#';

	if(preg_match_all($pattern, $str, $matches)) {
		list($search, $replace) = $matches;

		foreach($replace as $index => $key) {
			// replace with a empty string for missing matches
			$replace[$index] = Config::meta($key, '');
		}

		$str = str_replace($search, $replace, $str);
	}

	return $str;
}

/**
 * Converts a large number of bits into a readable size in bytes
 * rounded to two decimal places
 *
 * @param int|string
 * @return string
 */
function readable_size($size) {
	$unit = array('b','kb','mb','gb','tb','pb');

	return round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . ' ' . $unit[$i];
}