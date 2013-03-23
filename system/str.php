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

class Str {

	/**
	 * The string
	 *
	 * @var string
	 */
	private $string;


	/**
	 * Create a new instance of the Str class
	 *
	 * @param string
	 */
	public static function create($string = '') {
		return new static($string);
	}

	/**
	 * Str constructor
	 *
	 * @param string
	 */
	public function __construct($string = '') {
		$this->string = $string;
	}

	/**
	 * Strip tags
	 *
	 * @return object
	 */
	public function sanitize() {
		$this->string = filter_var($this->string, FILTER_SANITIZE_STRING);

		return $this;
	}

	/**
	 * Strips characters that has a numerical value >127 and <32.
	 *
	 * @return object
	 */
	public function strip() {
		$this->string = filter_var($this->string, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW, FILTER_FLAG_STRIP_HIGH);

		return $this;
	}

	/**
	 * Encodes characters with a numerical value >127 and <32.
	 *
	 * @return object
	 */
	public function encode() {
		$this->string = filter_var($this->string, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW, FILTER_FLAG_ENCODE_HIGH);

		return $this;
	}

	/**
	 * Get the string
	 *
	 * @return string
	 */
	public function value() {
		return $this->string;
	}

	/**
	 * Magic method to return raw string
	 *
	 * @return string
	 */
	public function __toString() {
		return $this->string;
	}

}