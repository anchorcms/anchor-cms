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

use System\Routing\Filters;

class Route {

	private $action, $args = array();

	public static $filters = array();

	public static function __callStatic($method, $arguments) {
		list($route, $action) = $arguments;

		if(is_string($route)) $route = array($route);

		foreach($route as $http) {
			Router::register($method, $http, $action);
		}
	}

	public static function filter($name, $action) {
		Filters::$actions[$name] = $action;
	}

	public function __construct($action, $args = array()) {
		$this->action = $action;
		$this->args = $args;
	}

	public function call() {
		$response = null;

		if(is_array($this->action)) {
			if(isset($this->action['before'])) {
				$response = Filters::run($this->action['before']);
			}

			$this->action = $this->action['do'];
		}

		if(is_null($response)) $response = call_user_func_array($this->action, $this->args);

		if($response instanceof Response) {
			return $response;
		}

		if($response instanceof View) {
			$response = $response->render();
		}

		return new Response($response);
	}

}