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

use Closure;
use Exception;
use ErrorException;

class Error {

	/**
	 * Holds function to logger
	 *
	 * @var mixed
	 */
	protected $logger;

	/**
	 * Exception output handler
	 *
	 * @var object
	 */
	protected $handler;

	/**
	 * Holds callback for reporting
	 *
	 * @var callable
	 */
	public static $callback;

	/**
	 * Setup error handler
	 */
	public static function setup(Closure $callback) {
		$callback(new static);
	}

	/**
	 * Set exception logger callback
	 *
	 * @param mixed
	 */
	public function logger($logger) {
		$this->logger = $logger;
	}

	/**
	 * Register callback
	 */
	public static function callback($callback) {
		static::$callback = $callback;
	}

	/**
	 * Register Exception handler
	 */
	public function register() {
		set_exception_handler(array($this, 'exception'));
		set_error_handler(array($this, 'native'));
		register_shutdown_function(array($this, 'shutdown'));
	}

	/**
	 * Unregister Exception handler
	 */
	public function unregister() {
		restore_exception_handler();
		restore_error_handler();
	}

	/**
	 * Exception handler
	 *
	 * @param object
	 */
	public function exception(Exception $e) {
		$this->log($e);

		if(Config::error('report')) {
			// try and clear any previous output
			ob_get_level() and ob_end_clean();

			// generate the output
			if(defined('STDIN')) {
				$handler = new Error\Handlers\Cli($e);
			}
			elseif(isset($_SERVER['HTTP_X_REQUESTED_WITH']) and strcasecmp($_SERVER['HTTP_X_REQUESTED_WITH'], 'xmlhttprequest') == 0) {
				$handler = new Error\Handlers\Json($e);
			}
			else {
				$handler = new Error\Handlers\Candy($e);
			}

			$handler->response();
		}
		elseif(static::$callback instanceof Closure) {
			call_user_func(static::$callback);
		}

		// exit with a error code
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
	public function native($code, $message, $file, $line) {
		if($code & error_reporting()) {
			$this->exception(new ErrorException($message, $code, 0, $file, $line));
		}
	}

	/**
	 * Shutdown handler
	 *
	 * This will catch errors that are generated at the
	 * shutdown level of execution
	 */
	public function shutdown() {
		if($error = error_get_last()) {
			extract($error);

			$this->native($type, $message, $file, $line);
		}
	}

	/**
	 * Exception logger
	 *
	 * Log the exception depending on the application config
	 *
	 * @param object
	 */
	public function log(Exception $e) {
		if(is_callable($this->logger)) {
			call_user_func($this->logger, $e);
		}
	}

}