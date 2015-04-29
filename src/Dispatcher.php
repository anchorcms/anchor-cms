<?php

use Events\EventManager;

class Dispatcher {

	protected $router;

	protected $events;

	public function __construct(Router $router, EventManager $events) {
		$this->router = $router;
		$this->events = $events;
	}

	protected function format($str) {
		$sep = '\\';
		$str = trim($str, $sep);
		$parts = array_map('ucfirst', explode($sep, $str));

		return $sep.implode($sep, $parts);
	}

	protected function dispatch($route, array $params) {
		if($route instanceof \Closure) return $route;

		list($class, $method) = explode('@', $route);

		$controller = $this->format($class);

		return [$controller, $method, $params];
	}

	public function match($uri) {
		$this->events->dispatch('routes', $this->router);

		if($this->router->has($uri)) {
			return $this->dispatch($this->router->get($uri), []);
		}

		foreach($this->router as $pattern => $route) {
			if(preg_match('#^'.$pattern.'$#', $uri, $matches)) {
				return $this->dispatch($route, array_slice($matches, 1));
			}
		}

		throw new \ErrorException(sprintf('No route matched %s', $uri));
	}

}
