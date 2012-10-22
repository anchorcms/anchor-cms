<?php namespace System\Session\Drivers;

use System\Config, System\Cookie as C;

class Cookie extends Driver {

	const payload = 'session_payload';

	public function load($id) {
		if(C::has(Cookie::payload)) {
			return unserialize(C::get(Cookie::payload));
		}
	}

	public function save($session, $config, $exists) {
		extract($config, EXTR_SKIP);

		$payload = serialize($session);

		C::put(Cookie::payload, $payload, $lifetime, $path, $domain);
	}

}