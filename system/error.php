<?php namespace System;

class Error {

	public static function exception($exception) {
		ob_get_level() and ob_end_clean();

		echo "<html><h2>Unhandled Exception</h2>
			<h3>Message:</h3>
			<pre>".$exception->getMessage()."</pre>
			<h3>Location:</h3>
			<pre>".$exception->getFile()." on line ".$exception->getLine()."</pre>
			<h3>Stack Trace:</h3>
			<pre>".$exception->getTraceAsString()."</pre></html>";

		exit(1);
	}

	public static function native($code, $error, $file, $line) {
		if(error_reporting() === 0) return;

		static::exception(new \ErrorException($error, $code, 0, $file, $line));
	}

	public static function shutdown() {
		if($error = error_get_last()) {
			extract($error, EXTR_SKIP);

			static::exception(new \ErrorException($message, $type, 0, $file, $line));
		}
	}

}