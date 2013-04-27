<?php namespace System\Session\Drivers;

/**
 * Nano
 *
 * Just another php framework
 *
 * @package		nano
 * @link		http://madebykieron.co.uk
 * @copyright	http://unlicense.org/
 */

use System\Session\Driver;

class Runtime extends Driver {

	public function read($id) {}

	public function write($id, $cargo) {}

}