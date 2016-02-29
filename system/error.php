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

use ErrorException;

class Error {

	/**
	 * Exception handler
	 *
	 * This will log the exception and output the exception properties
	 * formatted as html or a 500 response depending on your application config
	 *
	 * @param object The uncaught exception
	 */
	public static function exception($e) {
		static::log($e);

		if(Config::error('report')) {
			// clear output buffer
			while(ob_get_level() > 1) ob_end_clean();

			if(Request::cli()) {
				Cli::write(PHP_EOL . 'Uncaught Exception', 'light_red');
				Cli::write($e->getMessage() . PHP_EOL);

				Cli::write('Origin', 'light_red');
				Cli::write(substr($e->getFile(), strlen(PATH)) . ' on line ' . $e->getLine() . PHP_EOL);

				Cli::write('Trace', 'light_red');
				Cli::write($e->getTraceAsString() . PHP_EOL);
			}
			else {
				echo '<html>
					<head>
						<title>Uncaught Exception</title>
						<style>
							body{font-family:"Open Sans",arial,sans-serif;background:#FFF;color:#333;margin:0;background-color:#D7DCE3}
							code,pre{background:#f0f0f0;border-radius:4px;padding:10px;display:block;}
							h1{display:block;background-color:#444F5F;color:#fff;padding:30px;font-weight:100;margin:0}
							h3{display:block;background-color:#A4ADBB;color:#fff;padding:5px 30px;font-weight:100;margin:10px 0 0 0;border-radius:4px 4px 0 0;}
							.code-block{padding:10px 20px;}
							.brt{border-radius:0 0 4px 4px;}
							pre{margin:0;overflow:scroll;}
						</style>
					</head>
					<body>
						<h1>Uncaught Exception</h1>
						<div class="code-block">
							<h3>Error Message</h3>
							<code class="brt">' . $e->getMessage() . '</code>
							<h3>Origin</h3>
							<code class="brt">' . substr( $e->getFile(), strlen( PATH ) ) . ' on line ' . $e->getLine() . '</code>
							<h3>Trace</h3>
							<pre class="brt">' . $e->getTraceAsString() . '</pre>
						</div>
					</body>
					</html>';
			}
		}
		else {
			// issue a 500 response
			Response::error(500, array('exception' => $e))->send();
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
	public static function native($code, $message, $file, $line, $context) {
		if($code & error_reporting()) {
			static::exception(new ErrorException($message, $code, 0, $file, $line));
		}
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

			static::exception(new ErrorException($message, $type, 0, $file, $line));
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
