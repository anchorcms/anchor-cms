<?php namespace System;

class Uri {

	public static $uri, $url, $index;

	public static function make($uri) {
		if(strpos($uri, '://') !== false) return $uri;

		$base = str_finish(static::$url, '/');

		if(strlen(static::$index)) {
			$base .= static::$index . '/';
		}

		return $base . $uri;
	}

	public static function current() {
		if(static::$uri) return static::$uri;

		return static::$uri = static::detect();
	}

	private static function detect() {
		$attempts = array('PATH_INFO', 'REQUEST_URI');

		foreach($attempts as $variable) {
			if(isset($_SERVER[$variable])) {
				if(($uri = parse_url($_SERVER[$variable], PHP_URL_PATH)) === false) {
					throw new \ErrorException('Malformed request URI');
				}

				return static::format($uri);
			}
		}
	}

	private static function format($uri) {
		// First we want to remove the application's base URL from the URI if it is
		// in the string. It is possible for some of the parsed server variables to
		// include the entire document root in the string.
		$uri = static::remove_base($uri);

		// Next we'll remove the index file from the URI if it is there and then
		// finally trim down the URI. If the URI is left with spaces, we'll use
		// a single slash for the root URI.
		$uri = static::remove_index($uri);

		return trim($uri, '/') ?: '/';
	}

	private static function remove_base($uri) {
		if(is_null(static::$url)) {
			static::$url = Config::get('application.url');
		}

		return static::remove($uri, static::$url);
	}

	private static function remove_index($uri) {
		if(is_null(static::$index)) {
			static::$index = Config::get('application.index');
		}

		return static::remove($uri, '/' . static::$index);
	}

	private static function remove($uri, $value) {
		if( ! strlen($value)) return $uri;

		return (strpos($uri, $value) === 0) ? substr($uri, strlen($value)) : $uri;
	}

	public static function build($segments = array()) {
		// make sure we have all the fragments
		foreach(array('scheme', 'host', 'port', 'user', 'pass', 'path', 'query', 'fragment') as $fragment) {
			if( ! isset($segments[$fragment])) {
				// set missing default
				switch($fragment) {
					case 'scheme':
						$segments[$fragment] = 'http';
						break;
					case 'host':
						$segments[$fragment] = $_SERVER['HTTP_HOST'];
						break;
					default:
						$segments[$fragment] = '';
				}
			}
		}

		$url = $segments['scheme'] . '://';

		if($segments['user']) {
			$url .= $segments['user'];

			if($segments['pass']) {
				$url .= ':' . $segments['pass'];
			}

			$url .= '@';
		}

		$url .= trim($segments['host'], '/');

		if($segments['port']) {
			$url .= ':' . $segments['port'];
		}

		if($segments['path']) {
			$url .= '/' . trim($segments['path'], '/');
		}

		if($segments['query']) {
			if(is_array($segments['query'])) {
				$segments['query'] = http_build_query($segments['query']);
			}

			$url .= '?' . htmlentities($segments['query'], ENT_COMPAT, 'UTF-8', false);
		}

		if($segments['fragment']) {
			$url .= '#' . urlencode($segments['fragment']);
		}

		return $url;
	}

}