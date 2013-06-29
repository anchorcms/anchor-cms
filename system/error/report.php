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
use System\Request;

class Report {

	/**
	 * Get a object to return the appropriate message format
	 *
	 * @param object
	 * @param bool
	 * @return object
	 */
	public static function handler(Exception $exception, $detailed) {
		// clear output buffer
		ob_get_level() and ob_end_clean();

		if(defined('STDIN')) {
			return new Cli($exception, $detailed);
		}

		return new Html($exception, $detailed);
	}

}