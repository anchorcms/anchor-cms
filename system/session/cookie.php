<?php namespace System\Session;

/**
 * Nano
 *
 * Lightweight php framework
 *
 * @package		nano
 * @author		k. wilson
 * @link		http://madebykieron.co.uk
 */

use System\Config;
use System\Cookie as C;

class Cookie extends Driver {

	public $payload_cookie;

	public function __construct() {
		$this->payload_cookie = Config::get('session.cookie', 'session') . '_payload';
	}

	public function load($id) {
		if(C::has($this->payload_cookie)) {
			return unserialize(C::get($this->payload_cookie));
		}
	}

	public function save($session, $config, $exists) {
		extract($config, EXTR_SKIP);

		$payload = serialize($session);

		C::put($this->payload_cookie, $payload, $lifetime, $path, $domain);
	}

}