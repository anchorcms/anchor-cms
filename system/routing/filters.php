<?php namespace System\Routing;

/**
 * Nano
 *
 * Lightweight php framework
 *
 * @package		nano
 * @author		k. wilson
 * @link		http://madebykieron.co.uk
 */

class Filters {

	public static $actions = array();

	public static function run($name) {
		if(array_key_exists($name, static::$actions)) return call_user_func(static::$actions[$name]);
	}

}