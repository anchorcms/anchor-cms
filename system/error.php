<?php namespace System;

/**
 * Nano
 *
 * Lightweight php framework
 *
 * @package		nano
 * @author		k. wilson
 * @link		http://madebykieron.co.uk
 */

use ErrorException;

class Error {

	public static function native($code, $error, $file, $line) {
		// no error reporting nothing to do
		if(error_reporting() === 0) return;

		// For a PHP error, we'll create an ErrorExcepetion and then feed that
		// exception to the exception method, which will create a simple view
		// of the exception details for the developer.
		$exception = new ErrorException($error, $code, 0, $file, $line);

		if(in_array($code, Config::get('error.ignore'))) {
			return static::log($exception);
		}

		static::exception($exception);
	}

	public static function shutdown() {
		if($error = error_get_last()) {
			extract($error, EXTR_SKIP);

			static::exception(new ErrorException($message, $type, 0, $file, $line));
		}
	}

	public static function exception($exception) {
		static::log($exception);

		ob_get_level() and ob_end_clean();

		// show details or error
		if(Config::get('error.detail')) {
			echo "<html><h2>Unhandled Exception</h2>
				<h3>Message:</h3>
				<pre>".$exception->getMessage()."</pre>
				<h3>Location:</h3>
				<pre>".$exception->getFile()." on line ".$exception->getLine()."</pre>
				<h3>Stack Trace:</h3>
				<pre>".$exception->getTraceAsString()."</pre></html>";
		}
		// show 500 error
		else {
			Response::error(500)->send();
		}

		exit(1);
	}

	public static function log($exception) {
		if(Config::get('error.log')) {
			call_user_func(Config::get('error.logger'), $exception);
		}
	}

}