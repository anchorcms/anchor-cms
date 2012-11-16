<?php

class Assets {

	public static $assets = array();

	public static function format($paths) {
		return array_map(function($uri) {
			return str_finish(Config::get('application.url'), '/') . $uri;
		}, $paths);
	}

	public static function render($type) {
		if(empty(static::$assets[$type])) return '';

		$sorting = array();

		foreach(static::$assets[$type] as $key => $asset) {
			list($uri, $priority) = $asset;

			$sorting[$key] = $priority;
		}

		arsort($sorting);

		$html = '';

		foreach(array_keys($sorting) as $key) {
			list($uri, $priority) = static::$assets[$type][$key];

			if($type == 'js') {
				$html .= '<script src="' . $uri . '"></script>' . PHP_EOL;
			}
			else if($type == 'css') {
				$html .= '<link rel="stylesheet" href="' . $uri . '" type="text/css">' . PHP_EOL;
			}
		}

		return $html;
	}

	public static function __callStatic($method, $arguments) {
		$paths = array_shift($arguments);
		$priority = count($arguments) ? array_shift($arguments) : 10;

		if( ! isset(static::$assets[$method])) {
			static::$assets[$method] = array();
		}

		if( ! is_array($paths)) {
			$paths = array($paths);
		}

		$paths = static::format($paths);

		foreach($paths as $path) {
			$key = hash('crc32', $path);

			static::$assets[$method][$key] = array($path, $priority);
		}
	}

}