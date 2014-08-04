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
use Exception;

class Cookie extends Driver {

	public function read($id) {
		extract($this->config);

		// check if the cookie exists
		if($encoded = C::read($cookie . '_payload')) {
			// try decoding first
			if($decoded = base64_decode($encoded)) {
				// verify signature
				$sign = substr($decoded, 0, 32);
				$serialized = substr($decoded, 32);

				if(hash('md5', $serialized) == $sign) {
					return unserialize($serialized);
				}
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

		// serialize data into a srting
		$serialized = serialize($cargo);

		// create a signature to verify content when unpacking
		$sign = hash('md5', $serialized);

		// encode all the data
		$data = base64_encode($sign . $serialized);

		C::write($cookie . '_payload', $data, $lifetime, $path, $domain, $secure);
	}

}