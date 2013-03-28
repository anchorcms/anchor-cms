<?php

/**
 * Nano
 *
 * Just another php framework
 *
 * @package		nano
 * @link		http://madebykieron.co.uk
 * @copyright	http://unlicense.org/
 */

/**
 * Get a relative uri to be used with a view
 *
 * @example asset('styles.css');
 *
 * @param string
 * @return string
 */
function asset($uri) {
	return rtrim(Config::app('url'), '/') . '/' . $uri;
}

/**
 * Alias to class uri to method
 *
 * @param string
 * @return string
 */
function uri_to($uri) {
	return Uri::to($uri);
}

/**
 * Debugging function, simply a var_dump wrapper
 *
 * @example dd($something, $another);
 *
 * @param mixed
 */
function dd() {
	echo '<pre>';
	call_user_func_array('var_dump', func_get_args());
	echo '</pre>';
	exit;
}

/**
 * Generates a random string
 *
 * @param int Size
 * @return string
 */
function noise($size = 32) {
	$pool = 'abcefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';

	return substr(str_shuffle(str_repeat($pool, 3)), 0, $size);
}

/**
 * Normalise a string replacing foreign characters
 *
 * @param string
 * @return string
 */
function normalize($str) {
	$table = array(
		'Š'=>'S', 'š'=>'s', 'Đ'=>'Dj', 'đ'=>'dj', 'Ž'=>'Z', 'ž'=>'z', 'Č'=>'C', 'č'=>'c', 'Ć'=>'C', 'ć'=>'c',
		'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
		'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O',
		'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss',
		'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e',
		'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o',
		'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b',
		'ÿ'=>'y', 'Ŕ'=>'R', 'ŕ'=>'r',
	);

	return strtr($str, $table);
}

/**
 * Encode html to entities
 */
function e($str) {
	return htmlspecialchars($str, ENT_NOQUOTES, Config::app('encoding'), false);
}