<?php namespace System\Request;

/**
 * Nano
 *
 * Just another php framework
 *
 * @package		nano
 * @link		http://madebykieron.co.uk
 * @copyright	http://unlicense.org/
 */

class Server {

	/**
	 * Array data from the SERVER global
	 *
	 * @var array
	 */
	private $data;

	/**
	 * Server object constructor
	 *
	 * @param array
	 */
	public function __construct($array) {
		$this->data = $array;
	}

	/**
	 * Get a server array item
	 *
	 * @param string
	 */
	public function get($key, $fallback = null) {
		if(array_key_exists($key, $this->data)) {
			return $this->data[$key];
		}

		return $fallback;
	}

	/**
	 * Set a server array item
	 *
	 * @param string
	 * @param string
	 */
	public function set($key, $value) {
		$this->data[$key] = $value;
	}

	/**
	 * Check if a server array item exists
	 *
	 * @param string
	 */
	public function has($key) {
		return array_key_exists($key, $this->data);
	}

	/**
	 * Remove a server array item
	 *
	 * @param string
	 */
	public function erase($key) {
		if($this->has($key)) {
			unset($this->data[$key]);
		}
	}

}