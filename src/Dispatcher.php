<?php

class Dispatcher {

	protected $routes;

	protected $namespace;

	protected $events;

	protected $container;

	public function __construct(array $routes, $namespace, Events $events, Container $container) {
		$this->routes = $routes;
		$this->events = $events;
		$this->namespace = $namespace;
		$this->container = $container;
	}

	protected function formatClassName($str) {
		$sep = '\\';
		$str = trim($str, $sep);
		$parts = array_map('ucfirst', explode($sep, $str));

		return $sep.implode($sep, $parts);
	}

	protected function route($route) {
		if($route instanceof \Closure) return $route;

		list($class, $method) = explode('@', $route);

		$controller = $this->namespace . $this->formatClassName($class);

		return [new $controller($this->container), $method];
	}

	protected function dispatch($route, $params = []) {
		return call_user_func_array($this->route($route), $params);
	}

	public function match($uri) {
		if(array_key_exists($uri, $this->routes)) {
			return $this->dispatch($this->routes[$uri]);
		}

		foreach($this->routes as $pattern => $route) {
			if(preg_match('#^'.$pattern.'$#', $uri, $matches)) {
				return $this->dispatch($route, array_slice($matches, 1));
			}
		}

		throw new \ErrorException(sprintf('No route matched %s', $uri));
	}

}
