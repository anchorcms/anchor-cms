<?php namespace System;

/**
 * Nano
 *
 * Lightweight php framework
 *
 * @package		nano
 * @author		k. wilson
 * @link		http://madebykieron.co.uk
 */

use PDO, System\Database\Connection;

class Database {

	public static $connections = array();

	public static function connection($connection = null) {
		if(is_null($connection)) $connection = Config::get('database.default');

		if( ! isset(static::$connections[$connection])) {
			$config = Config::get("database.connections.{$connection}");

			if(is_null($config)) {
				throw new \Exception("Database connection is not defined for [$connection].");
			}

			static::$connections[$connection] = new Connection(static::connect($config), $config);
		}

		return static::$connections[$connection];
	}

	public static function connect($config) {
		// build dns string
		$parts = array('dbname=' . $config['database'], 'host=' . $config['hostname']);

		if(isset($config['port'])) {
			$parts[] = 'port=' . $config['port'];
		}

		if(isset($config['charset'])) {
			$parts[] = 'charset=' . $config['charset'];
		}

		$dsn = 'mysql:' . implode(';', $parts);

		return new PDO($dsn, $config['username'], $config['password'], array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
	}

	public static function __callStatic($method, $parameters) {
		return call_user_func_array(array(static::connection(), $method), $parameters);
	}

}