<?php

use Composer\Script\Event;

class App {

	protected $container;

	protected $install = false;

	public function __construct($container) {
		$this->container = $container;
	}

	public function __get($key) {
		return $this->container[$key];
	}

	public function checkInstall() {
		// if the config folder has not been created run the installer
		return is_dir(__DIR__ . '/../app/config') === false || filter_input(INPUT_GET, 'installer', FILTER_SANITIZE_STRING) !== null;
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
		$enabled = explode(',', $this->container['meta']->key('active_plugins', ''));
		$paths = $this->container['config']->get('paths');

		foreach($enabled as $name) {
			$path = $paths['plugins'] . '/' . $name . '/plugin.php';

			if(is_file($path)) {
				try {
					require $path;
					$class = 'Plugins\\' . str_replace(' ', '', ucwords(str_replace('-', ' ', $name)));

					$obj = new $class($this->container);
					$obj->init();
				}
				catch(\Exception $e) {
					throw new \ErrorException(sprintf('Uncaught Exception in plugin: ', $name), 0, 1, __FILE__, __LINE__, $e);
				}
			}
		}

		$this->events->trigger('pluginsLoaded');
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

	public function run() {
		$output = $this->dispatcher->match($this->http->getUri());
		$this->session->rotate();

		$this->events->trigger('output', $output);

		echo $output;
	}

}
