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
		$uri = preg_replace('#^' . URL_PATH . '#', '', $uri);
		
		return trim($uri, '/');
	}	
	
}
