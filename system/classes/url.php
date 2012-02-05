<?php defined('IN_CMS') or die('No direct access allowed.');

class Url {

	private static $base_url, $index_page;

	public static function make($uri = '') {
		if(empty(static::$base_url)) {
			static::$base_url = Config::get('application.base_url');
		}
		
		if(empty(static::$index_page)) {
			static::$index_page = Config::get('application.index_page') ? Config::get('application.index_page') . '/' : '';
		}

		return static::$base_url . static::$index_page . ltrim($uri, '/');
	}
	
	public static function build($segments = array()) {
		// make sure we have all the fragments
		foreach(array('scheme', 'host', 'port', 'user', 'pass', 'path', 'query', 'fragment') as $fragment) {
			if(isset($segments[$fragment]) === false) {
				// set missing default
				switch($fragment) {
					case 'scheme':
						$segments[$fragment] = 'http';
						break;
					case 'host':
						$segments[$fragment] = Input::server('http_host');
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
			$url .= '/' . ltrim($segments['path'], '/');
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

	public static function current() {
		return parse_url(Input::server('REQUEST_URI'), PHP_URL_PATH);
	}

	public static function title($str) {
		$search = '_';
		$replace = '-';

		$trans = array(
			'&\#\d+?;' => '',
			'&\S+?;' => '',
			'\s+' => $replace,
			'[^a-z0-9\-\._]' => '',
			$replace.'+' => $replace,
			$replace.'$' => '',
			'^'.$replace => '',
			'\.+$' => ''
		);

		$str = strip_tags($str);

		foreach($trans as $key => $val) {
			$str = preg_replace("#" . $key . "#i", $val, $str);
		}

		$str = strtolower($str);

		return trim(stripslashes($str));
	}

}
