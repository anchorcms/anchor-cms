<?php namespace System\Error;

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
use System\Error\Message;
use System\Cli as C;

class Cli extends Message {

	/**
	 * Cli Exception error message
	 *
	 * @param object
	 */
	public function response() {
		C::write(PHP_EOL . 'Uncaught Exception', 'light_red');
		C::write($this->exception->getMessage() . PHP_EOL);

		C::write('Origin', 'light_red');

		$file = substr($this->exception->getFile(), strlen(PATH));
		C::write($file . ' on line ' . $this->exception->getLine() . PHP_EOL);

		C::write('Trace', 'light_red');
		C::write($this->exception->getTraceAsString() . PHP_EOL);
	}

}