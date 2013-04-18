<?php namespace System\Error;

/**
 * Nano
 *
 * Just another php framework
 *
 * @package		nano
 * @link		http://madebykieron.co.uk
 * @copyright	http://unlicense.org/
 */

use Exception;

abstract class Message {

	protected $exception;

	/**
	 * Creates a message object for the exception
	 *
	 * @param object
	 */
	public function __construct(Exception $exception) {
		$this->exception = $exception;
	}

	/**
	 * Returned message string
	 */
	abstract public function response();

}