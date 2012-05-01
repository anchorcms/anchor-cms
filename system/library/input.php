<?php defined('IN_CMS') or die('No direct access allowed.');

class Input {

	public static function clean($str) {
		// handle magic quotes for people who cant turn it off
		if(function_exists('get_magic_quotes_gpc')) {
			if(get_magic_quotes_gpc()) {
				return is_array($str) ? array_map(array('Input', 'clean'), $str) : stripslashes($str);
			}
		}

		return $str;
	}

	private static function fetch_array($array, $key, $default = false) {
		if(is_array($key)) {
			$data = array();

			foreach($key as $k) {
				$data[$k] = static::fetch_array($array, $k, $default);
			}

			return $data;
		}
		
		if(array_key_exists($key, $array)) {
			return static::clean($array[$key]);
		}

		return ($default instanceof \Closure) ? call_user_func($default) : $default;
	}

	public static function post($key, $default = false) {
		return static::fetch_array($_POST, $key, $default);
	}

	public static function get($key, $default = false) {
		return static::fetch_array($_GET, $key, $default);
	}

	public static function put($key, $default = false) {
		return static::fetch_array(parse_str(file_get_contents('php://input')), $key, $default);
	}

	public static function delete($key, $default = false) {
		return static::fetch_array(parse_str(file_get_contents('php://input')), $key, $default);
	}

	public static function cookie($key, $default = false) {
		return static::fetch_array($_COOKIE, $key, $default);
	}

	public static function server($key, $default = false) {
		return static::fetch_array($_SERVER, strtoupper($key), $default);
	}

	public static function file($key, $default = false) {
		return static::fetch_array($_FILES, $key, $default);
	}

	public static function method() {
		return static::server('REQUEST_METHOD');
	}

	public static function ip_address() {
		 // IP from share internet
		if (static::server('REMOTE_ADDR') and static::server('HTTP_CLIENT_IP')) {
			return static::server('HTTP_CLIENT_IP');
		}

		if(static::server('REMOTE_ADDR')) {
			return static::server('REMOTE_ADDR');
		}

		if(static::server('HTTP_CLIENT_IP')) {
			return static::server('HTTP_CLIENT_IP');
		}

		// Proxy detection
		if(static::server('HTTP_X_FORWARDED_FOR')) {
			return static::server('HTTP_X_FORWARDED_FOR');
		}

		return '0.0.0.0';
	}

	public static function user_agent() {
		return static::server('HTTP_USER_AGENT');
	}

}
