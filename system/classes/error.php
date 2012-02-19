<?php defined('IN_CMS') or die('No direct access allowed.');

class Error {

	public static function native($code, $error, $file, $line) {
		// no error reporting nothing to do
		if(error_reporting() === 0) {
			return;
		}

		$exception = new ErrorException($error, $code, 0, $file, $line);

		if(in_array($code, Config::get('error.ignore', array()))) {
			return static::log($exception);
		}

		static::exception($exception);
	}
	
	public static function shutdown() {
		if(!is_null($error = error_get_last())) {
			extract($error, EXTR_SKIP);
			static::exception(new ErrorException($message, $type, 0, $file, $line));
		}
	}

	public static function exception($e) {
		// Clean the output buffer.
		if(ob_get_level() > 0) {
			ob_clean();
		}

		// log exception
		static::log($e);

		// Display error
		if(Config::get('error.detail', true)) {
			// Get the error file.
			$file = $e->getFile();

			// Trim the period off of the error message.
			$message = rtrim($e->getMessage(), '.');

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

	public static function log($e) {
		if(Config::get('error.log')) {
			Log::exception($e);
		}
	}

}
