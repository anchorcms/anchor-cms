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

use InvalidArgumentException;

class Route {

	/**
	 * Array of callable functions
	 *
	 * @var array
	 */
	public $actions;

	/**
	 * The collected arguments from the uri match
	 *
	 * @var array
	 */
	public $args = array();

	/**
	 * Define a route using the method name as
	 * the request method to listen for
	 *
	 * @param string
	 * @param array
	 */
	public static function __callStatic($method, $arguments) {
		list($patterns, $args) = $arguments;

		static::register($method, $patterns, $args);
	}

	/**
	 * Register a route to add to the router
	 *
	 * @param string
	 * @param array|string
	 * @param array
	 */
	public static function register($method, $patterns, $args) {
		if( ! is_array($patterns)) {
			$patterns = array($patterns);
		}

		if(is_callable($args)) {
			$args = array('main' => $args);
		}

		if( ! isset($args['main'])) {
			throw new InvalidArgumentException('No `main` index was passed');
		}

		foreach($patterns as $pattern) {
			Router::$routes[strtoupper($method)][$pattern] = $args;
		}
	}

	/**
	 * Register a action to be called on before or after
	 *
	 * @param string
	 * @param string|closure
	 */
	public static function action($name, $func) {
		Router::$actions[$name] = $func;
	}

	/**
	 * Create a new instance of the Route class
	 *
	 * @param array
	 * @param array
	 */
	public function __construct($funcs, $args = array()) {
		$this->actions = $funcs;
		$this->args = $args;
	}

	/**
	 * Calls the before action
	 *
	 * @return object
	 */
	public function before() {
		if(isset($this->actions['before'])) {
			$name = $this->actions['before'];

			if(isset(Router::$actions[$name])) {
				return call_user_func_array(Router::$actions[$name], $this->args);
			}
		}
	}

	/**
	 * Calls the after action
	 *
	 * @param string
	 */
	public function after($response) {
		if(isset($this->actions['after'])) {
			$name = $this->actions['after'];

			if(isset(Router::$actions[$name])) {
				call_user_func(Router::$actions[$name], $response);
			}
		}
	}

	/**
	 * Calls the route actions and returns a response object
	 *
	 * @return object
	 */
	public function run() {
		$response = $this->before();

		if(is_null($response)) {
			$response = call_user_func_array($this->actions['main'], $this->args);
		}

		$this->after($response);

		// Create a response from a View
		if($response instanceof View) {
			$response = Response::create($response->yield());
		}

		// Create a response from a String
		if(is_string($response)) {
			$response = Response::create($response);
		}

		return $response;
	}

}