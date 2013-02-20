<?php namespace System;

/**
 * Nano
 *
 * Just another php framework
 *
 * @package		nano
 * @link		http://madebykieron.co.uk
 * @copyright	http://unlicense.org/
 */

use FilesystemIterator;
use ErrorException;

class Router {

	/**
	 * The current URI
	 *
	 * @var str
	 */
	public $uri;

	/**
	 * Array of regex patterns to subsitute
	 * in defined routes
	 *
	 * @var array
	 */
	public static $patterns = array(
		':any' => '[^/]+',
		':num' => '[0-9]+',
		':all' => '.*'
	);

	/**
	 * The defined routes set by the app
	 *
	 * @var array
	 */
	public static $routes = array();

	/**
	 * Actions to call before and after routes
	 *
	 * @var array
	 */
	public static $actions = array();

	/**
	 * Create a new instance of the Router class for chaining
	 *
	 * @return object
	 */
	public static function create($uri) {
		return new static($uri);
	}

	/**
	 * Create a new instance of the Router class and import
	 * app routes from a folder or a single routes.php file
	 */
	public function __construct($uri) {
		// the current uri
		$this->uri = $uri;

		// read all files and nested files
		if(is_dir($path = APP . 'routes')) {
			$this->read($path);
		}

		// read single file
		if(is_readable($path = APP . 'routes' . EXT)) {
			require $path;
		}
	}

	/**
	 * Read app routes from a directory recursively
	 *
	 * @param string
	 */
	public function read($path) {
		// try and match uri with filesystem so we only include
		// files relative to our uri and not everything
		foreach(explode($this->uri, '/') as $segment) {
			if(is_dir($dir = $path . DS . $segment)) {
				$this->import($dir);
			}

			if(file_exists($file = $path . DS . $segment . EXT)) {
				require $file;
			}

			$path .= DS . $segment;
		}
	}

	/**
	 * Import app routes from a directory recursively
	 *
	 * @param string
	 */
	public function import($path) {
		$iterator = new FilesystemIterator($path, FilesystemIterator::SKIP_DOTS);

		foreach($iterator as $fileinfo) {
			if($fileinfo->getExtension() == 'php') {
				require $fileinfo->getPathname();
			}
			else if($fileinfo->isDir()) {
				$this->import($fileinfo->getPathname());
			}
		}
	}

	/**
	 * Try and match the request method and uri with defined routes
	 *
	 * @param string The request method
	 * @param string The current uri
	 * @return object Return a instance of a Route
	 */
	public function match($method) {
		$routes = array_merge(
			Arr::get(static::$routes, $method, array()),
			Arr::get(static::$routes, 'ANY', array())
		);

		// try a simple match
		if(array_key_exists($this->uri, $routes)) {
			return new Route($routes[$this->uri]);
		}

		// search for patterns
		foreach($routes as $pattern => $action) {
			// replace wildcards
			if(strpos($pattern, ':') !== false) {
				$pattern = str_replace(array_keys(static::$patterns), array_values(static::$patterns), $pattern);
			}

			if(preg_match('#^' . $pattern . '$#', $this->uri, $matched)) {
				return new Route($action, array_slice($matched, 1));
			}
		}

		throw new ErrorException('No routes matched');
	}

}