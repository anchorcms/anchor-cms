<?php namespace System\Error\Handlers;

/**
 * Nano
 *
 * Just another php framework
 *
 * @package		nano
 * @link		http://madebykieron.co.uk
 * @copyright	http://unlicense.org/
 */

use System\Error\Handler;

class Cli extends Handler {

	/**
	 * Check if php is running on windows
	 *
	 * @return bool
	 */
	public function is_windows() {
		return 'win' === strtolower(substr(php_uname("s"), 0, 3));
	}

	/**
	 * Write to stdout
	 *
	 * @param string
	 */
	public function write($text) {
		fwrite(STDOUT, $text . PHP_EOL);
	}

	/**
	 * Write to stdout in a light red colour
	 *
	 * @param string
	 */
	public function highlight($text) {
		if($this->is_windows()) {
			$this->write($text);
		}
		else $this->write(sprintf("\033[1;31m%s\033[0m", $text));
	}

	/**
	 * Cli Exception error message
	 */
	public function response() {
		$this->highlight(PHP_EOL . 'Uncaught Exception');
		$this->write($this->exception->getMessage() . PHP_EOL);
		$this->write($this->exception->getFile() . ':' . $this->exception->getLine() . PHP_EOL);

		$this->highlight('Trace');
		$this->write($this->exception->getTraceAsString() . PHP_EOL);
	}

}