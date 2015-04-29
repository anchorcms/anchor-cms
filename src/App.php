<?php

class App {

	protected $container;

	protected $install = false;

	public function __construct($container) {
		$this->container = $container;
	}

	public function __get($key) {
		return $this->container[$key];
	}

	public function isInstallerRunning() {
		return filter_input(INPUT_GET, 'installer', FILTER_SANITIZE_STRING) !== null;
	}

	public function isInstalled() {
		// mssing config folder
		if(is_dir(__DIR__ . '/../app/config') === false) {
			return false;
		}

		// check db connection
		try {
			$config = require __DIR__ . '/../app/config/db.php';
			$installer = new Services\Installer;
			$installer->getPdo($config);
		}
		catch(Exception $e) {
			return false;
		}

		return true;
	}

	public function registerErrorHandler() {
		$this->errors->register();
	}

	public function removeTrailingSlash() {
		$uri = $this->http->getUri();

		if($uri != '/' && substr($uri, -1) == '/') {
			$uri = rtrim($uri, '/');

			header('Location: ' . $uri, true, 301);
			exit;
		}
	}

	public function loadPlugins() {
		$enabled = explode(',', $this->meta->key('active_plugins', ''));
		$paths = $this->config->get('paths');

		foreach($enabled as $name) {
			$path = $paths['plugins'] . '/' . $name . '/plugin.php';

			if(is_file($path)) {
				require $path;
				$class = 'Plugins\\' . str_replace(' ', '', ucwords(str_replace('-', ' ', $name)));

				$obj = new $class($this->container);
				$obj->init();
			}
		}

		//$this->events->dispatch('plugins.loaded');
	}

	public function runInstall() {
		$controller = new Controllers\Installer\Install($this->container);
		$action = filter_input(INPUT_GET, 'installer', FILTER_SANITIZE_STRING, ['options' => ['default' => 'index']]);
		$verb = filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_STRING, ['options' => ['default' => 'GET']]);

		// format case
		$method = strtolower($verb) . ucfirst(strtolower($action));

		$output = $controller->$method();
		$this->session->rotate();
		echo $output;
	}

	public function routes(Router $router) {
		$router->append($this->config->get('routes'));
	}

	public function registerEvents() {
		$this->events->listen('routes', [$this, 'routes']);
	}

	public function run() {
		list($controller, $method, $params) = $this->dispatcher->match($this->http->getUri());

		// prepend namespace
		$controller = '\\Controllers' . $controller;

		$class = new $controller($this->container);
		$output = call_user_func_array([$class, $method], $params);

		$this->session->rotate();

		echo $output;
	}

}
