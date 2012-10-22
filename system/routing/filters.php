<?php namespace System\Routing;

class Filters {

	public static $actions = array();

	public static function run($name) {
		if(array_key_exists($name, static::$actions)) return call_user_func(static::$actions[$name]);
	}

}