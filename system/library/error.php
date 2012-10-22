<?php defined('IN_CMS') or die('No direct access allowed.');

class Error {

	public static function native($code, $error, $file, $line) {
		// no error reporting nothing to do
		if(error_reporting() === 0) return;

		$exception = new ErrorException($error, $code, 0, $file, $line);

		// error code to ignore
		$ignore = Config::get('error.ignore', array());

		if(in_array($code, $ignore)) {
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
		ob_get_level() and ob_end_clean();

		// log exception
		static::log($e);

		// Display error
		if(Config::get('error.detail')) {
			// Get the error file.
			$file = $e->getFile();

			// Trim the period off of the error message.
			$message = rtrim($e->getMessage(), '.');

			$line = $e->getLine();
			$trace = $e->getTraceAsString();

			require PATH . 'system/admin/theme/error_php.php';
		} else {
			require PATH . 'system/admin/theme/error_500.php';
		}

		exit(1);
	}

	public static function log($e) {
		if(Config::get('error.log')) {
			Log::exception($e);
		}
	}

}
