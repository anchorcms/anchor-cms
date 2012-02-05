<?php defined('IN_CMS') or die('No direct access allowed.');

class Request {

	public static function uri_segment($index, $default = false) {
		$index--;
		$segments = explode('/', static::uri());
		return isset($segments[$index]) ? $segments[$index] : $default;
	}
	
	public static function uri() {
		if(isset($_SERVER['PATH_INFO'])) {
			$uri = $_SERVER['PATH_INFO'];
		}
		// try request uri
		elseif(isset($_SERVER['REQUEST_URI'])) {
			// make sure we can parse URI
			if(($uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)) === false) {
				throw new Exception('Malformed request URI');
			}
		}
		// cannot process request
		else {
			throw new Exception('Unable to determine the request URI');
		}

		// remove base url
		$base_url = parse_url(Config::get('application.base_url'), PHP_URL_PATH);

		if(strlen($base_url)) {
			if(strpos($uri, $base_url) === 0) {
				$uri = substr($uri, strlen($base_url));
			}
		}

		// remove index file
		$index = '/' . Config::get('application.index_page');

		if(strpos($uri, $index) === 0) {
			$uri = substr($uri, strlen($index));
		}

		return trim($uri, '/');
	}	
	
}
