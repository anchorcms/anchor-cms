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

class Arr {

	/**
	 * Return a element from a array
	 *
	 * @param array
	 * @param string
	 * @param mixed
	 */
	public static function get($array, $key, $fallback = null) {
		// search the array using the dot character to access nested array values
		foreach($keys = explode('.', $key) as $key) {
			// when a key is not found or we didnt get an array to search return a fallback value
			if( ! is_array($array) or ! array_key_exists($key, $array)) {
				return $fallback;
			}

			$array =& $array[$key];
		}

		return $array;
	}

	/**
	 * Sets a value in a array
	 *
	 * @param array
	 * @param string
	 * @param mixed
	 */
	public static function set(&$array, $key, $value) {
		$keys = explode('.', $key);

		// traverse the array into the second last key
		while(count($keys) > 1) {
			$key = array_shift($keys);

			// make sure we have a array to set our new key in
			if( ! array_key_exists($key, $array)) {
				$array[$key] = array();
			}

			$array =& $array[$key];
		}

		$array[array_shift($keys)] = $value;
	}

	/**
	 * Remove a value from a array
	 *
	 * @param array
	 * @param string
	 */
	public static function erase(&$array, $key) {
		$keys = explode('.', $key);

		// traverse the array into the second last key
		while(count($keys) > 1) {
			$key = array_shift($keys);

			if(array_key_exists($key, $array)) {
				$array =& $array[$key];
			}
		}

		// if the last key exists unset it
		if(array_key_exists($key = array_shift($keys), $array)) {
			unset($array[$key]);
		}
	}

	/**
	 * Create a new instance of the Arr class
	 *
	 * @param array
	 */
	public static function create($stack = array()) {
		return new static($stack);
	}

	/**
	 * Arr constructor
	 *
	 * @param array
	 */
	public function __construct($stack = array()) {
		$this->stack = $stack;
	}

	/**
	 * Shuffle the array elements in the stack
	 *
	 * @return object Returns self for chaining
	 */
	public function shuffle() {
		shuffle($this->stack);

		return $this;
	}

	/**
	 * Returns the first element in the stack
	 *
	 * @return mixed
	 */
	public function first() {
		return current($this->stack);
	}

	/**
	 * Returns the last element in the stack
	 *
	 * @return mixed
	 */
	public function last() {
		return end($this->stack);
	}

}