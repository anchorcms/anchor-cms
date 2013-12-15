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

use System\Response\Status;

class Response {

	/**
	 * The final output
	 *
	 * @var string
	 */
	public $output;

	/**
	 * The response status
	 *
	 * @var int
	 */
	public $status = 200;

	/**
	 * Array of headers to be sent
	 *
	 * @var array
	 */
	public $headers = array();

	/**
	 * Create a new instance of the Response class for chaining
	 *
	 * @param string
	 * @param int
	 * @param array
	 * @return object
	 */
	public static function create($output, $status = 200, $headers = array()) {
		return new static($output, $status, $headers);
	}

	/**
	 * Creates a response with a header to redirect
	 *
	 * @param string
	 * @param int
	 * @return object
	 */
	public static function redirect($uri, $status = 302) {
		// Scrub all output buffer before we redirect.
		// @see http://www.mombu.com/php/php/t-output-buffering-and-zlib-compression-issue-3554315-last.html
		while(ob_get_level() > 1) ob_end_clean();

		return static::create('', $status, array('Location' => Uri::to($uri)));
	}

	/**
	 * Creates a response with the output as error view from the app
	 * along with the status code
	 *
	 * @param int
	 * @return object
	 */
	public static function error($status, $vars = array()) {
		return static::create(View::create('error/' . $status, $vars)->render(), $status);
	}

	/**
	 * Creates a response with the output as JSON
	 *
	 * @param string
	 * @param int
	 * @return object
	 */
	public static function json($output, $status = 200) {
		return static::create(json_encode($output), $status,
			array('content-type' => 'application/json; charset=' . Config::app('encoding', 'UTF-8')));
	}

	/**
	 * Create a new instance of the Response class
	 *
	 * @param string
	 * @param int
	 * @param array
	 */
	public function __construct($output, $status = 200, $headers = array()) {
		$this->status = $status;
		$this->output = $output;

		foreach($headers as $name => $value) {
			$this->headers[strtolower($name)] = $value;
		}
	}

	/**
	 * Sends the final headers cookies and output
	 */
	public function send() {
		// dont send headers for CLI
		if( ! Request::cli()) {
			// create a status header
			Status::create($this->status)->header();

			// always make sure we send the content type
			if( ! array_key_exists('content-type', $this->headers)) {
				$this->headers['content-type'] = 'text/html; charset=' . Config::app('encoding', 'UTF-8');
			}

			// output headers
			foreach($this->headers as $name => $value) {
				header($name . ': ' . $value);
			}

			// send any cookies we may have stored in the cookie class
			foreach(Cookie::$bag as $cookie) {
				call_user_func_array('setcookie', array_values($cookie));
			}
		}

		// output the final content
		if($this->output) echo $this->output;
	}

}