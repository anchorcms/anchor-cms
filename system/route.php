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
use Response;
use View;
use Closure;

class Route {

	/**
	 * Array of collection actions
	 *
	 * @var array
	 */
	public static $collection = array();

	/**
	 * Array of callable functions
	 *
	 * @var array
	 */
	public $callbacks;

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
		static::register($method, array_shift($arguments), array_shift($arguments));
	}

	/**
	 * Register a route on the router
	 *
	 * @param string
	 * @param array|string
	 * @param array|closure
	 */
	public static function register($method, $patterns, $arguments) {
		$method = strtoupper($method);

		if($arguments instanceof \Closure) {
			$arguments = array('main' => $arguments);
		}

		// add collection actions
		$arguments = array_merge($arguments, static::$collection);

		foreach((array) $patterns as $pattern) {
			Router::$routes[$method][$pattern] = $arguments;
		}
	}

	/**
	 * Register a action on the router
	 *
	 * @param string
	 * @param string|closure
	 */
	public static function action($name, $callback) {
		Router::$actions[$name] = $callback;
	}

	/**
	 * Start a collection of routes with common actions
	 *
	 * @param string
	 * @param string|closure
	 */
	public static function collection($actions, $definitions) {
		// start collection
		static::$collection = $actions;

		// run definitions
		call_user_func($definitions);

		// end of collection
		static::$collection = array();
	}

	/**
	 * Create a new instance of the Route class
	 *
	 * @param array
	 * @param array
	 */
	public function __construct($callbacks, $args = array()) {
		$this->callbacks = $callbacks;
		$this->args = $args;
	}

	/**
	 * Calls before actions
	 *
	 * @return object
	 */
	public function before() {
		if( ! isset($this->callbacks['before'])) return;

		foreach(explode(',', $this->callbacks['before']) as $action) {
			// return the first response object
			if($response = call_user_func_array(Router::$actions[$action], $this->args)) {
				return $response;
			}
		}
	}

	/**
	 * Calls after actions
	 *
	 * @param string
	 */
	public function after($response) {
		if( ! isset($this->callbacks['after'])) return;

		foreach(explode(',', $this->callbacks['after']) as $action) {
			call_user_func(Router::$actions[$action], $response);
		}
	}

	/**
	 * Calls the route actions and returns a response object
	 *
	 * @return object
	 */
	public function run() {
		// Call before actions
		$response = $this->before();

		// If we didn't get a response run the main callback
		if(is_null($response)) {
			$response = call_user_func_array($this->callbacks['main'], $this->args);
		}

		// Call any after actions
		$this->after($response);

		// If the response was a view get the output and create response
		if($response instanceof \System\View) {
			return Response::create($response->render());
		}

		// If we have a response object return it
		if($response instanceof \System\Response) {
			return $response;
		}

		return Response::create((string) $response);
	}

}