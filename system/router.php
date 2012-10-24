<?php namespace System;

use FilesystemIterator;

class Router {

	public static $routes = array(
		'get' => array(),
		'post' => array()
	);

	public static $patterns = array(
		'(:num)' => '([0-9]+)',
		'(:any)' => '([a-zA-Z0-9\.\-_%]+)',
		'(:all)' => '(.*)'
	);

	public static function load($path = null) {
		if(is_null($path)) $path = APP . 'routes';

		// register routes
		$fi = new FilesystemIterator($path, FilesystemIterator::SKIP_DOTS);

		foreach($fi as $file) {
			if($file->isDir()) {
				static::load($file->getPathname());
			}

			if($file->isFile() and $file->isReadable() and pathinfo($file->getPathname(), PATHINFO_EXTENSION) == 'php') {
				require $file->getPathname();
			}
		}

		// sorting
		foreach(array_keys(static::$routes) as $method) {
			krsort(static::$routes[$method]);
		}
	}

	public static function register($method, $uri, $action) {
		static::$routes[$method][trim($uri)] = $action;
	}

	public static function route($method, $uri) {
		if($route = static::match($method, $uri)) {
			return $route;
		}
	}

	public static function method($method) {
		if(isset(static::$routes['any'])) {
			foreach(static::$routes['any'] as $route => $action) {
				if(array_key_exists($route, static::$routes[$method])) {
					continue;
				}

				static::$routes[$method][$route] = $action;
			}
		}

		return static::$routes[$method];
	}

	public static function match($method, $uri) {
		foreach(static::method($method) as $route => $action) {
			// try simple match
			if($uri == $route) {
				return new Route($action);
			}

			// search for patterns
			if(strpos($route, '(') !== false) {
				foreach(static::$patterns as $search => $replace) {
					if(strpos($route, $search) !== false) {
						// swap out placeholders for regex
						$route = str_replace($search, $replace, $route);
					}
				}

				if(preg_match('#^' . $route . '$#', $uri, $matched)) {
					return new Route($action, array_slice($matched, 1));
				}
			}

			// search for wild card
			if(strpos($route, '*') !== false) {
				return new Route($action);
			}
		}
	}

}