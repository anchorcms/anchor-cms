<?php namespace System;

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
	    //  Set defaults
	    if(!isset($config['port'])) {
	        $config['port'] = 3306;
	    }
	    
		// build dns string
		$dsn = implode(';', array($config['driver'] . ':dbname=' . $config['database'], 'host=' . $config['hostname'],
			':port=' . $config['port'], 'charset=' . $config['charset']));

		return new PDO($dsn, $config['username'], $config['password'], array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
	}

	public static function __callStatic($method, $parameters) {
		return call_user_func_array(array(static::connection(), $method), $parameters);
	}

}