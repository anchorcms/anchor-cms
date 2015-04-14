<?php

class Dispatcher {

	protected $container;

	protected $routes;

	public function __construct(Container $container) {
		$this->routes = $container['config']->get('routes');
		$this->container = $container;
	}

	protected function formatClassName($str) {
		return implode('\\', array_map('ucfirst', explode('\\', $str)));
	}

	protected function dispatch($route, $params = []) {
		list($class, $method) = explode('@', $route);

		$controller = $this->formatClassName($class);

		// built-in controller prefix with controller
		if(strpos($class, 'plugins\\') === false) {
			$controller = '\\Controllers\\' . $controller;
		}

		$this->container['events']->trigger('dispatch', $controller, $method);

		return call_user_func_array([new $controller($this->container), $method], $params);
	}

	public function match($uri) {
		// load routes from plugins
		$pluginRoutes = $this->container['events']->triggerMerge('routes');

		// prepend custom routes
		$this->routes = $pluginRoutes + $this->routes;

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
