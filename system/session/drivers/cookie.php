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

		// check if the cookie exists
		if($encoded = C::read($cookie . '_payload')) {
			// can we decode and unserialize it
			if($data = @unserialize(base64_decode($encoded))) {
				return $data;
			}
		}
	}

	public function write($id, $cargo) {
		extract($this->config);

		// if the session is set to never expire
		// we will set it to 1 year
		if($lifetime == 0) {
			$lifetime = (3600 * 24 * 365);
		}

		$data = base64_encode(serialize($cargo));

		C::write($cookie . '_payload', $data, $lifetime, $path, $domain, $secure);
	}

}