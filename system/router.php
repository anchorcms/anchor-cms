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

use FilesystemIterator;

class Router {

	public static $routes = array();

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
		if($route = static::match(strtolower($method), $uri)) {
			return $route;
		}
	}

	public static function match($method, $uri) {
		foreach(static::method($method) as $route => $action) {
			// try simple match
			if($uri == $route) {
				return new Route($action);
			}

			// replace any pre-defined patterns
			if(str_contains($route, '(')) {
				$route = str_replace(array_keys(static::$patterns), array_values(static::$patterns), $route);

				// search for patterns
				if(preg_match('#^' . $route . '$#', $uri, $matched)) {
					return new Route($action, array_slice($matched, 1));
				}
			}

			// search for wild card
			if(str_contains($route, '*')) {
				return new Route($action);
			}
		}
	}

	public static function method($method) {
		// If there are routes defined as any we copy them
		// into the requested method routes array
		if(isset(static::$routes['any'])) {
			foreach(static::$routes['any'] as $route => $action) {
				// If the requested method route is defined
				// skip the `any` route
				if(array_key_exists($route, static::$routes[$method])) {
					continue;
				}

				static::$routes[$method][$route] = $action;
			}
		}

		return static::$routes[$method];
	}


}