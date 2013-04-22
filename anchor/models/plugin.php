<?php

class Plugin extends Base {

	public static $table = 'plugins';

	public static $hooks = array();

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