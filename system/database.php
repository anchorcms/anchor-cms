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
use Exception;

class Database {

	/**
	 * The current database driver
	 *
	 * @var array
	 */
	public static $connections = array();

	/**
	 * Create a new database conncetor from app config
	 *
	 * @param array
	 * @return object Database connector
	 */
	public static function factory($config) {
		switch($config['driver']) {
			case 'mysql':
				return new Database\Connectors\Mysql($config);
			case 'sqlite':
				return new Database\Connectors\Sqlite($config);
		}

		throw new ErrorException('Unknown database driver');
	}

	/**
	 * Get a database connection by name r return the default
	 *
	 * @param string
	 * @return object
	 */
	public static function connection($name = null) {
		// use the default connection if none is specified
		if(is_null($name)) $name = Config::db('default');

		// if we have already connected just return the instance
		if(isset(static::$connections[$name])) return static::$connections[$name];

		// connect and return
		return (static::$connections[$name] = static::factory(Config::db('connections.' . $name)));
	}

	/**
	 * Get a database connection profile
	 *
	 * @param string
	 * @return array
	 */
	public static function profile($name = null) {
		return static::connection($name)->profile();
	}

	/**
	 * Magic method for calling database driver methods on the default connection
	 *
	 * @param string
	 * @param array
	 * @return mixed
	 */
	public static function __callStatic($method, $arguments) {
		return call_user_func_array(array(static::connection(), $method), $arguments);
	}

}