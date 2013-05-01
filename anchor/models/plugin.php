<?php

class Plugin extends Base {

	public static $folder = 'plugins';

	public static $table = 'plugins';

	public static $hooks = array();

	/**
	 * Parse the about txt file
	 */
	public static function about($pathname) {
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
	public static function installed() {
		return static::get();
	}

	/**
	 * Returns a list of installed plugins
	 */
	public static function valid($pathname) {
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
	public static function available() {
		$fi = new FilesystemIterator(PATH . static::$folder, FilesystemIterator::SKIP_DOTS);
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
	public function include_functions() {
		$path = PATH . static::$folder . DS . $this->path . DS . 'functions' . EXT;

		if(file_exists($path)) {
			return require $path;
		}
	}

	/**
	 * Create instance of the user plugin
	 */
	public function instance() {
		$path = PATH . static::$folder . DS . $this->path . DS . 'plugin' . EXT;

		if(file_exists($path)) {
			try {
				if( ! class_exists($this->path, false)) require $path;

				$ref = new ReflectionClass($this->path);

				$instance = $ref->newInstance();

				return $instance::find($this->id);
			} catch(Exception $e) {
				throw new Exception('There was a problem running ' . $this->name, 0, $e);
			}
		}
	}

	/**
	 * Returns the templates 404 page
	 */
	protected function page_not_found() {
		return Response::create(new Template('404'), 404);
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
	 * Installer placeholder
	 */
	public function install() {}

	/**
	 * Uninstaller placeholder
	 */
	public function uninstall() {}

	/**
	 * Registers routes with router
	 */
	final function apply_routes() {
		if( ! method_exists($this, 'register_routes')) return;

		foreach($this->register_routes() as $verb => $actions) {
			foreach($actions as $patterns => $action) {
				Route::register($verb, $patterns, array('main' => $action));
			}
		}
	}

	/**
	 * Registers routes with router
	 */
	final function apply_protected_routes() {
		if( ! method_exists($this, 'register_protected_routes')) return;

		foreach($this->register_protected_routes() as $verb => $actions) {
			foreach($actions as $patterns => $action) {
				Route::register($verb, $patterns, array('before' => 'auth', 'main' => $action));
			}
		}
	}

	/**
	 * Registers content filters
	 */
	final function apply_filters() {
		if( ! method_exists($this, 'register_filters')) return;

		foreach($this->register_filters() as $object => $properties) {
			foreach($properties as $property => $action) {
				static::$hooks[$object][$property] = $action;
			}
		}
	}

}