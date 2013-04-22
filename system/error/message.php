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

	/**
	 * @var object Exception
	 */
	protected $exception;

	/**
	 * @var bool
	 */
	protected $detailed;

	/**
	 * Creates a message object for the exception
	 *
	 * @param object
	 */
	public function __construct(Exception $exception, $detailed) {
		$this->exception = $exception;
		$this->detailed = $detailed;
	}

	/**
	 * Returned message string
	 */
	abstract public function response();

}