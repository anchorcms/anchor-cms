<?php namespace System\Response;

/**
 * Nano
 *
 * Just another php framework
 *
 * @package		nano
 * @link		http://madebykieron.co.uk
 * @copyright	http://unlicense.org/
 */

use System\Request;

class Status {

	/**
	 * The status code
	 *
	 * @var int
	 */
	public $status;

	/**
	 * The server protocol
	 *
	 * @var string
	 */
	public $protocol;

	/**
	 * Array or possible server status responses
	 *
	 * @var array
	 */
	public $messages = array(
		100 => 'Continue',
		101 => 'Switching Protocols',
		200 => 'OK',
		201 => 'Created',
		202 => 'Accepted',
		203 => 'Non-Authoritative Information',
		204 => 'No Content',
		205 => 'Reset Content',
		206 => 'Partial Content',
		207 => 'Multi-Status',
		300 => 'Multiple Choices',
		301 => 'Moved Permanently',
		302 => 'Found',
		303 => 'See Other',
		304 => 'Not Modified',
		305 => 'Use Proxy',
		307 => 'Temporary Redirect',
		400 => 'Bad Request',
		401 => 'Unauthorized',
		402 => 'Payment Required',
		403 => 'Forbidden',
		404 => 'Not Found',
		405 => 'Method Not Allowed',
		406 => 'Not Acceptable',
		407 => 'Proxy Authentication Required',
		408 => 'Request Timeout',
		409 => 'Conflict',
		410 => 'Gone',
		411 => 'Length Required',
		412 => 'Precondition Failed',
		413 => 'Request Entity Too Large',
		414 => 'Request-URI Too Long',
		415 => 'Unsupported Media Type',
		416 => 'Requested Range Not Satisfiable',
		417 => 'Expectation Failed',
		422 => 'Unprocessable Entity',
		423 => 'Locked',
		424 => 'Failed Dependency',
		500 => 'Internal Server Error',
		501 => 'Not Implemented',
		502 => 'Bad Gateway',
		503 => 'Service Unavailable',
		504 => 'Gateway Timeout',
		505 => 'HTTP Version Not Supported',
		507 => 'Insufficient Storage',
		509 => 'Bandwidth Limit Exceeded'
	);

	/**
	 * Create an instance or the Status class for chaining
	 *
	 * @param int
	 * @return object
	 */
	public static function create($status = 200) {
		return new static($status);
	}

	/**
	 * Create an instance or the Status class
	 *
	 * @param int
	 */
	public function __construct($status = 200) {
		$this->status = $status;
		$this->protocol = Request::protocol();
	}

	/**
	 * Send the status header
	 */
	public function header() {
		// status message
		$message = $this->messages[$this->status];

		// for fastcgi we have to send a status header like so:
		// http://php.net/manual/en/function.header.php
		if(strpos(PHP_SAPI, 'cgi') !== false) {
			header('Status: ' . $this->status . ' ' . $message);
		}
		// overwise we just send a normal status header
		else {
			header($this->protocol . ' ' . $this->status .  ' ' . $message);
		}
	}

}