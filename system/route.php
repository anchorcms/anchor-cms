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

use Closure;
use InvalidArgumentException;
use System\Response;
use System\Router;
use System\View;

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

		if($arguments instanceof Closure) {
			$arguments = array('main' => $arguments);
		}

		// add collection actions
		if(count(static::$collection)) {
			$arguments = array_merge($arguments, end(static::$collection));
		}

		if( ! is_array($patterns)) {
			$patterns = array($patterns);
		}

		foreach($patterns as $pattern) {
			Router::$routes[$method][$pattern] = $arguments;
		}
	}

	/**
	 * Register the 404 not found callback
	 *
	 * @param closure
	 */
	public static function not_found($callbacks) {
		if($callbacks instanceof Closure) {
			$callbacks = array('main' => $callbacks);
		}

		Router::$not_found = $callbacks;
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
		static::$collection[] = $actions;

		// run definitions
		call_user_func($definitions);

		// end of collection
		array_pop(static::$collection);
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
	 * @return object|null
	 */
	public function before() {
		return $this->callback(__METHOD__);
	}

	/**
	 * Calls after actions
	 *
	 * @param string
	 * @return object|null
	 */
	public function after($response) {
		return $this->callback(__METHOD__);
	}

	/**
	 * Run actions
	 *
	 * @param string
	 * @return object|null
	 */
	public function callback($name) {
		if(isset($this->callbacks[$name])) {
			foreach(explode(',', $this->callbacks[$name]) as $action) {
				return call_user_func(Router::$actions[$action], $response);
			}
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

		// If we have a response object return it
		if($response instanceof Response) {
			return $response;
		}

		// If the response was a view get the output and create response
		if($response instanceof View) {
			$response = $response->render();
		}
		// Invoke object tostring method
		elseif(is_object($response) and method_exists($response, '__toString')) {
			$response = (string) $response;
		}
		// capture any echo'd output
		elseif(ob_get_length()) {
			$response = ob_get_clean();
		}

		return Response::create($response);
	}

}