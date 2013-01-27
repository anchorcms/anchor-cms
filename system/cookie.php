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

class Cookie {

	public static $jar = array();

	public static function has($name) {
		return isset(static::$jar[$name]) or array_key_exists($name, $_COOKIE);
	}

	public static function get($name, $default = null) {
		if(isset(static::$jar[$name])) return static::$jar[$name]['value'];

		return array_key_exists($name, $_COOKIE) ? $_COOKIE[$name] : $default;
	}

	public static function put($name, $value, $expiration = 0, $path = '/', $domain = null, $secure = false) {
		if($expiration !== 0) {
			$expiration = time() + $expiration;
		}

		static::$jar[$name] = compact('name', 'value', 'expiration', 'path', 'domain', 'secure');
	}

	public static function forget($name, $path = '/', $domain = null, $secure = false) {
		return static::put($name, null, -2000, $path, $domain, $secure);
	}

}