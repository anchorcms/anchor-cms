<?php

class Errors {

	public function register() {
		set_error_handler([$this, 'native']);
		set_exception_handler([$this, 'exception']);
		register_shutdown_function([$this, 'shutdown']);
	}

	public function exception($exception) {
		ob_get_level() && ob_end_clean();

		echo '<p><code>Uncaught exception: '.$exception->getMessage().'</code></p>
			<p><code>'.$exception->getFile().'('.$exception->getLine().')</code></p>
			<pre>'.$exception->getTraceAsString().'</pre>';

		exit(1);
	}

	public function native($code, $message, $file, $line) {
		if($code & error_reporting()) {
			$this->exception(new \ErrorException($message, $code, 0, $file, $line));
		}

		return false;
	}

	public function shutdown() {
		if($error = error_get_last()) {
			extract($error);

			$this->native($type, $message, $file, $line);
		}
	}

}
