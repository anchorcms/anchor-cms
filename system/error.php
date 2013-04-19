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

use Exception;
use ErrorException;

class Error {

	/**
	 * Register Exception handler
	 */
	public static function register() {
		set_exception_handler(array('Error', 'exception'));
		set_error_handler(array('Error', 'native'));
		register_shutdown_function(array('Error', 'shutdown'));
	}

	/**
	 * Unregister Exception handler
	 */
	public static function unregister() {
		restore_exception_handler();
		restore_error_handler();
	}

	/**
	 * Exception handler
	 *
	 * @param Exception $e
	 */
	public static function exception(Exception $e) {
		static::log($e);

		if(Config::error('report')) {
			// clear output buffer
			ob_end_clean();

			if(Request::cli()) {
				$output = new Error\Cli($e);
			}
			else {
				$output = new Error\Html($e);
			}

			$output->response();
		}
		else {
			// output a 500 response
			Response::error(500)->send();
		}

		exit(1);
	}

	/**
	 * Error handler
	 *
	 * This will catch the php native error and treat it as a exception
	 * which will provide a full back trace on all errors
	 *
	 * @param int
	 * @param string
	 * @param string
	 * @param int
	 * @param array
	 */
	public static function native($code, $message, $file, $line) {
		static::exception(new ErrorException($message, $code, 0, $file, $line));
	}

	/**
	 * Shutdown handler
	 *
	 * This will catch errors that are generated at the
	 * shutdown level of execution
	 */
	public static function shutdown() {
		if($error = error_get_last()) {
			extract($error);

			static::native($type, $message, $file, $line);
		}
	}

	/**
	 * Exception logger
	 *
	 * Log the exception depending on the application config
	 *
	 * @param object The exception
	 */
	public static function log($e) {
		if(is_callable($logger = Config::error('log'))) {
			call_user_func($logger, $e);
		}
	}

}