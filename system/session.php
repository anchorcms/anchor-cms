<?php namespace System;

/**
 * Nano
 *
 * Just another php framework
 *
 * @package		nano
 * @link		http://madebykieron.co.uk
 * @copyright	http://unlicense.org/
 */

use ErrorException;
use System\Session\Cargo;

class Session {

	/**
	 * Holds an instance of the session driver
	 *
	 * @var array
	 */
	public static $cargo;

	/**
	 * Create a new session driver object
	 *
	 * @param array
	 */
	public static function factory($config) {
		switch($config['driver']) {
			case 'memcache':
				return new Session\Drivers\Memcache($config);
			case 'memcached':
				return new Session\Drivers\Memcached($config);
			case 'cookie':
				return new Session\Drivers\Cookie($config);
			case 'database':
				return new Session\Drivers\Database($config);
			case 'runtime':
				return new Session\Drivers\Runtime($config);
		}

		throw new ErrorException('Unknown session driver');
	}

	/**
	 * Returns the curren instance of the cargo object
	 *
	 * @return object Cargo
	 */
	public static function instance() {
		if(is_null(static::$cargo)) {
			$driver = static::factory(Config::session());

			static::$cargo = new Cargo($driver, Config::app('key'));
		}

		return static::$cargo;
	}

	/**
	 * Read the current session using the driver set in the config file
	 */
	public static function read() {
		if(is_null(static::$cargo)) {
			$driver = static::factory(Config::session());

			static::$cargo = new Cargo($driver, Config::app('key'));
		}

		static::instance()->read();
	}

	/**
	 * Write the current session using the driver set in the config file
	 */
	public static function write() {
		static::instance()->write();
	}

	/**
	 * Magic method to call a method on the session driver
	 *
	 * @param string
	 * @param array
	 */
	public static function __callStatic($method, $arguments) {
		$cargo = static::instance();

		if(method_exists($cargo, $method)) {
			return call_user_func_array(array($cargo, $method), $arguments);
		}

		throw new ErrorException('Unknown session method');
	}

}