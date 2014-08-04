<?php namespace System\Session;

/**
 * Nano
 *
 * Just another php framework
 *
 * @package		nano
 * @link		http://madebykieron.co.uk
 * @copyright	http://unlicense.org/
 */

abstract class Driver {

	/**
	 * The session config array
	 *
	 * @var array
	 */
	public $config;

	/**
	 * Create a new instance of a driver
	 *
	 * @param array
	 */
	public function __construct($config) {
		$this->config = $config;
	}

	/**
	 * The session read prototype
	 */
	abstract public function read($id);

	/**
	 * The session write prototype
	 *
	 * @param int
	 * @param object
	 */
	abstract public function write($id, $cargo);

}