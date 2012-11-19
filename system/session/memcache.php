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

use System\Session\Driver;
use System\Config;
use ErrorException;
use Memcache as MCache;

class Memcache extends Driver {

	private $memcached;

	public function __construct() {
		extract(Config::get('cache.memcache'), EXTR_SKIP);

		$this->memcached = new MCache;

		if($this->memcached->connect($host, $port) === false) {
			throw new ErrorException('Failed to connect to memcache on ' . $host . ':' . $port);
		}
	}

	public function load($id) {
		if($value = $this->memcached->get($id)) {
			return $value;
		}
	}

	public function save($session, $config, $exists) {
		return $this->memcached->set($session['id'], $session, false, $config['lifetime']);
	}

}