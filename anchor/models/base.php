<?php

class Base extends Record {

	public static function table($name = '') {
		if(is_null(static::$prefix)) {
			static::$prefix = Config::db('prefix', '');
		}

		if($name) return static::$prefix . $name;

		return static::$prefix . static::$table;
	}

	public static function __callStatic($method, $arguments) {
		$obj = Query::table(static::table())->apply(get_called_class());

		if(method_exists($obj, $method)) {
			return call_user_func_array(array($obj, $method), $arguments);
		}
	}

	public function __get($key) {
		if(array_key_exists($key, $this->data)) {
			$object = strtolower(get_called_class());

			if(isset(Plugin::$hooks[$object][$key])) {
				return call_user_func(Plugin::$hooks[$object][$key], $this->data[$key]);
			}

			return $this->data[$key];
		}
	}

}