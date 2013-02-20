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

use System\Cookie as C;
use System\Session\Driver;

class Cookie extends Driver {

	public function read($id) {
		extract($this->config);

		if($data = C::read($cookie . '_payload')) {
			return unserialize(base64_decode($data));
		}
	}

	public function write($id, $cargo) {
		extract($this->config);

		$data = base64_encode(serialize($cargo));

		C::write($cookie . '_payload', $data, $lifetime, $path, $domain, $secure);
	}

}