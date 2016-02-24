<?php namespace System;

/**
 * Nano
 *
 * Just another php framework
 *
 * @package		nano
 * @link		http://madebykieron.co.uk
 * @copyright	http://unlicense.org/
 */

class Input {

	/**
	 * Array or request vars
	 *
	 * @var array
	 */
	public static $array;

	/**
	 * Try and collect the request input determinded
	 * by the request method
	 *
	 * @param string
	 */
	public static function detect($method) {
		switch($method) {
			case 'GET':
				$query = parse_url(Arr::get($_SERVER, 'REQUEST_URI'), PHP_URL_QUERY);
				parse_str($query, static::$array);
				break;

			case 'POST':
				static::$array = $_POST;
				break;

			default:
				parse_str(file_get_contents('php://input'), static::$array);
		}
	}

	/**
	 * Get a element or array of elements from the input array
	 *
	 * @param string|array
	 * @param mixed
	 * @return mixed
	 */
	public static function get($key, $fallback = null) {
		if(is_array($key)) return static::get_array($key, $fallback);

		$data = Arr::get(static::$array, $key, $fallback);

		if (is_string($data)) {
			return e($data);
		}

		return $data;
	}

	/**
	 * Get a array of elements from the input array
	 *
	 * @param array
	 * @param mixed
	 * @return array
	 */
	public static function get_array($array, $fallback = null) {
		$values = array();

		foreach($array as $key) {
			$values[$key] = static::get($key, $fallback);
		}

		return $values;
	}

	/**
	 * Save the input array for the next request
	 */
	public static function flash() {
		Session::flash(static::$array);
	}

	/**
	 * Get a element from the previous request input array
	 *
	 * @param string
	 * @param mixed
	 * @return mixed
	 */
	public static function previous($key, $fallback = null) {
		return Arr::get(Session::flash(), $key, $fallback);
	}

}