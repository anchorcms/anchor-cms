<?php defined('IN_CMS') or die('No direct access allowed.');

class Error {

	public static $levels = array(
		0 => 'Error',
		E_ERROR => 'Error',
		E_WARNING => 'Warning',
		E_PARSE => 'Parsing Error',
		E_NOTICE => 'Notice',
		E_CORE_ERROR => 'Core Error',
		E_CORE_WARNING => 'Core Warning',
		E_COMPILE_ERROR => 'Compile Error',
		E_COMPILE_WARNING => 'Compile Warning',
		E_USER_ERROR => 'User Error',
		E_USER_WARNING => 'User Warning',
		E_USER_NOTICE => 'User Notice',
		E_STRICT => 'Runtime Notice'
	);

	public static function native($code, $error, $file, $line) {
		if(($code & error_reporting()) === $code) {
			static::exception(new \ErrorException($error, $code, 0, $file, $line));
		}
	}
	
	public static function shutdown() {
		// If a fatal error occured that we have not handled yet, we will
		// create an ErrorException and feed it to the exception handler,
		// as it will not have been handled by the error handler.
		if(($error = error_get_last()) !== null) {
			extract($error, EXTR_SKIP);
			static::exception(new \ErrorException($message, $type, 0, $file, $line));
		}
	}

	public static function exception($e) {
		// Clean the output buffer.
		if(ob_get_level() > 0) {
			ob_clean();
		}

		// Get the error severity.
		$severity = (array_key_exists($e->getCode(), static::$levels)) ? static::$levels[$e->getCode()] : $e->getCode();

		// Get the error file.
		$file = $e->getFile();

		// Trim the period off of the error message.
		$message = rtrim($e->getMessage(), '.');

		// Log the error.
		if(Config::get('debug')) {
			$line = $e->getLine();
			$trace = $e->getTraceAsString();
			$contexts = static::context($file, $e->getLine());

			require PATH . 'system/admin/theme/error_php.php';
		} else {
			require PATH . 'system/admin/theme/error_500.php';
		}

		exit(1);
	}

	private static function context($path, $line, $padding = 5) {
		if(file_exists($path)) {
			$file = file($path, FILE_IGNORE_NEW_LINES);

			array_unshift($file, '');

			// Calculate the starting position.
			$start = $line - $padding;

			if($start < 0) {
				$start = 0;
			}

			// Calculate the context length.
			$length = ($line - $start) + $padding + 1;

			if(($start + $length) > count($file) - 1) {
				$length = null;
			}

			return array_slice($file, $start, $length, true);
		}

		return array();
	}
}
