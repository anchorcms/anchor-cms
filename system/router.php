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
use InvalidArgumentException;

class Router {

	/**
	 * The current URI
	 *
	 * @var str
	 */
	public $uri;

	/**
	 * The current request method
	 *
	 * @var str
	 */
	public $method;

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
	public static function create() {
		if(Request::cli()) {
			// get cli arguments
			$args = Arr::get($_SERVER, 'argv', array());

			$uri = implode('/', array_slice($args, 1));

			return new static('cli', trim($uri, '/') ?: '/');
		}

		return new static(Request::method(), Uri::current());
	}

	/**
	 * Create a new instance of the Router class and import
	 * app routes from a folder or a single routes.php file
	 *
	 * @param string
	 * @param string
	 */
	public function __construct($method, $uri) {
		$this->uri = $uri;
		$this->method = strtoupper($method);
	}

	/**
	 * Gets array of request method routes
	 *
	 * @return array
	 */
	public function routes() {
		$routes = array();

		if(array_key_exists($this->method, static::$routes)) {
			$routes = array_merge($routes, static::$routes[$this->method]);
		}

		if(array_key_exists('ANY', static::$routes)) {
			$routes = array_merge($routes, static::$routes['ANY']);
		}

		return $routes;
	}

	/**
	 * Try and match the request method and uri with defined routes
	 *
	 * @return object Return a instance of a Route
	 */
	public function match() {
		$routes = $this->routes();

		// try a simple match
		if(array_key_exists($this->uri, $routes)) {
			return new Route($routes[$this->uri]);
		}

		// search for patterns
		$searches = array_keys(static::$patterns);
		$replaces = array_values(static::$patterns);

		foreach($routes as $pattern => $action) {
			// replace wildcards
			if(strpos($pattern, ':') !== false) {
				$pattern = str_replace($searches, $replaces, $pattern);
			}

			// slice array of matches. $matches[0] will contain the text that
			// matched the full pattern, $matches[1] will have the text that
			// matched the first captured parenthesized subpattern, and so on.
			if(preg_match('#^' . $pattern . '$#', $this->uri, $matched)) {
				return new Route($action, array_slice($matched, 1));
			}
		}

		if(isset(static::$routes['ERROR']['404'])) {
			return new Route(static::$routes['ERROR']['404']);
		}

		throw new ErrorException('No routes matched');
	}

	/**
	 * Match the request with a route and run it
	 *
	 * @return object Response instance
	 */
	public function dispatch() {
		return $this->match()->run();
	}

}