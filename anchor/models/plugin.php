<?php

class Plugin extends Base {

	public static $folder = 'plugins';

	public static $table = 'plugins';

	public static $callbacks = array();

	/**
	 * Parse the about txt file
	 */
	final public static function about($pathname) {
		$info = array(
			'path' => $pathname,
			'name' => '',
			'description' => '',
			'version' => ''
		);

		$path = PATH . static::$folder . DS . $pathname . DS . 'about.txt';

		foreach(file($path) as $line) {
			list($key, $value) = explode(':', $line, 2);

			$info[trim(strtolower($key))] = trim($value);
		}

		return $info;
	}

	/**
	 * Returns a list of installed plugins
	 */
	final public static function installed() {
		return static::get();
	}

	/**
	 * Returns a list of installed plugins
	 */
	final public static function valid($pathname) {
		$base = PATH . static::$folder . DS . $pathname . DS;

		$required = array('about.txt', 'plugin' . EXT);

		foreach($required as $file) {
			if( ! file_exists($base . $file)) return false;
		}

		return true;
	}

	/**
	 * Returns a list of available plugins
	 */
	final public static function available() {
		$fi = new Filesystem(PATH . static::$folder, Filesystem::SKIP_DOTS);
		$plugins = array();

		foreach($fi as $fileinfo) {
			if($fileinfo->isDir()) {
				$pathname = $fileinfo->getBasename();

				// validate plugin
				if(static::valid($pathname)) {
					$plugins[] = static::about($pathname);
				}
			}
		}

		return $plugins;
	}

	/**
	 * Include theming functions
	 */
	final public function include_functions() {
		$path = PATH . static::$folder . DS . $this->path . DS . 'functions' . EXT;

		if(file_exists($path)) {
			return require_once $path;
		}

		return $this;
	}

	/**
	 * Create instance of the user plugin
	 */
	final public function instance() {
		$path = PATH . static::$folder . DS . $this->path . DS . 'plugin' . EXT;

		if(file_exists($path)) {
			try {
				$class = preg_replace('/\W+/', '', $this->path);

				if( ! class_exists($class, false)) {
					require $path;
				}

				$ref = new ReflectionClass($class);

				$instance = $ref->newInstance();

				return $instance::find($this->id);
			} catch(Exception $e) {
				throw new Exception('There was a problem running ' . $this->name, 0, $e);
			}
		}
	}

	/**
	 * Returns the plugin path of a file argument
	 */
	protected function plugin_path($file = '') {
		$file = strlen($file) ? trim($file, DS) . EXT : '';

		return PATH . 'plugins/' . strtolower($this->path) . DS . $file;
	}

	/**
	 * Returns the theme path of a file argument
	 */
	protected function theme_path($file = '') {
		$theme = Config::meta('theme');

		$file = strlen($file) ? trim($file, DS) . EXT : '';

		return PATH . 'themes/' . $theme . DS . $file;
	}

	/**
	 * Placeholders
	 */
	public function install() {}
	public function uninstall() {}
	public function register_routes() {}
	public function register_protected_routes() {}
	public function register_filters() {}

	/**
	 * Registers routes with router
	 */
	final function apply_routes() {
		$routes = $this->register_routes();

		if(is_array($routes) and count($routes)) {
			foreach($routes as $verb => $actions) {
				foreach($actions as $pattern => $action) {
					Route::register($verb, $pattern, array('main' => $action));
				}
			}
		}

		return $this;
	}

	/**
	 * Registers routes with router
	 */
	final function apply_protected_routes() {
		$routes = $this->register_protected_routes();

		if(is_array($routes) and count($routes)) {
			foreach($routes as $verb => $actions) {
				foreach($actions as $pattern => $action) {
					$pattern = 'admin/extend/plugins/' . $this->path . '/' . ltrim($pattern, '/');

					Route::register($verb, $pattern, array('before' => 'auth', 'main' => $action));
				}
			}
		}

		return $this;
	}

	/**
	 * Registers content filters
	 */
	final function apply_filters() {
		$filters = $this->register_filters();

		if(is_array($filters) and count($filters)) {
			foreach($filters as $object => $properties) {
				foreach($properties as $property => $action) {
					static::$callbacks[$object . '.' . $property][] = $action;
				}
			}
		}

		return $this;
	}

}