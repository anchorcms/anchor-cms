<?php defined('IN_CMS') or die('No direct access allowed.');

class Request {

	public static function uri_segment($index, $default = false) {
		$index--;
		$segments = explode('/', static::uri());
		return isset($segments[$index]) ? $segments[$index] : $default;
	}

	public static function uri() {
		$uri = static::detect();

		$uri = static::remove_base($uri);

		return trim($uri, '/');
	}

	private static function detect() {
		foreach(array('PATH_INFO', 'REQUEST_URI') as $method) {
			if(isset($_SERVER[$method])) {
				if(($uri = parse_url($_SERVER[$method], PHP_URL_PATH)) === false) {
					throw new Exception('Malformed request URI');
				}

				return $uri;
			}
		}
	}

	private static function remove_base($uri) {
		// remove base url
		if($base = rtrim(Config::get('application.base_url'), '/')) {
			$uri = static::remove($uri, $base);
		}

		// remove index file
		if($index = Config::get('application.index_page')) {
			$uri = static::remove($uri, '/' . $index);
		}

		return $uri;
	}

	private static function remove($uri, $str) {
		if(strpos($uri, $str) === 0) {
			return substr($uri, strlen($str));
		}

		return $uri;
	}

}