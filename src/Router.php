<?php

use Events\Event;

class Router extends Event implements IteratorAggregate {

	protected $routes;

	public function __construct(array $routes = []) {
		$this->routes = $routes;
	}

	public function getIterator() {
		return new ArrayIterator($this->routes);
	}

	public function get($key, $default = null) {
		return array_key_exists($key, $this->routes) ? $this->routes[$key] : $default;
	}

	public function put($key, $value) {
		$this->routes[$key] = $value;
	}

	public function has($key) {
		return array_key_exists($key, $this->routes);
	}

	public function append(array $routes) {
		$this->routes = array_merge($this->routes, $routes);
	}

}
