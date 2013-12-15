<?php

class Base extends Record {

	public static function table($name = null) {
		if(is_null(static::$prefix)) {
			static::$prefix = Config::db('prefix', '');
		}

		if( ! is_null($name)) return static::$prefix . $name;

		return static::$prefix . static::$table;
	}

	public static function __callStatic($method, $arguments) {
		$obj = Query::table(static::table())->apply(get_called_class());

		if(method_exists($obj, $method)) {
			return call_user_func_array(array($obj, $method), $arguments);
		}
	}

}