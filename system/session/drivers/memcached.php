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

use Memcached as M;
use System\Config;
use System\Session\Driver;

class Memcached extends Driver {

	private $key;

	public $server;

	public function __construct($config) {
		$this->config = $config;
		$this->key = Config::app('key');

		// setup the memcache server
		extract(Config::cache('memcached'));

		$this->server = new M;
		$this->server->addServer($host, $port);
	}

	public function read($id) {
		if($data = $this->server->get($this->key . '_' . $id)) {
			return unserialize($data);
		}
	}

	public function write($id, $cargo) {
		extract($this->config);

		$this->server->set($this->key . '_' . $id, serialize($cargo), $lifetime);
	}

}