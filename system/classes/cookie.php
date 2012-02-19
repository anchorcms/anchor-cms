<?php defined('IN_CMS') or die('No direct access allowed.');

class Cookie {

	public static function has($key) {
		return isset($_COOKIE[$key]);
	}
	
	public static function get($key, $default = false) {
		if(static::has($key)) {
			return $_COOKIE[$key];
		}
		
		return ($default instanceof \Closure) ? call_user_func($default) : $default;
	}
	
	public static function write($name, $data, $expire, $path, $domain) {
		if(headers_sent() === false) {
			return setcookie($name, $data, $expire, $path, $domain, false);
		}

		return false;
	}

}
