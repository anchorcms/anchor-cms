<?php namespace System;

/**
 * Nano
 *
 * Just another php framework
 *
 * @package		nano
 * @link		http://madebykieron.co.uk
 * @copyright	http://unlicense.org/
 */

class View {

	/**
	 * The current path to view file
	 *
	 * @var string
	 */
	public $path;

	/**
	 * Array of view variables
	 *
	 * @var array
	 */
	public $vars = array();

	/**
	 * Create a instance or the View class for chaining
	 *
	 * @param string
	 * @param array
	 * @return object
	 */
	public static function create($path, $vars = array()) {
		return new static($path, $vars);
	}

	/**
	 * Create a instance of a View class for chaining using a
	 * method for the file name
	 *
	 * @example View::home(array('title' => 'Home'));
	 *
	 * @param string
	 * @param array
	 * @return object
	 */
	public static function __callStatic($method, $arguments) {
		$vars = count($arguments) ? current($arguments) : array();
		return new static($method, $vars);
	}

	/**
	 * Create a instance or the View class
	 *
	 * @param string
	 * @param array
	 */
	public function __construct($path, $vars = array()) {
		$this->path = APP . 'views/' . $path . EXT;
		$this->vars = array_merge($this->vars, $vars);
	}

	/**
	 * Render a partial view
	 *
	 * @return string
	 */
	public function partial($name, $path, $vars = array()) {
		$this->vars[$name] = static::create($path, $vars)->render();

		return $this;
	}

	/**
	 * Render the view
	 *
	 * @return string
	 */
	public function render() {
		ob_start();

		extract($this->vars);

		require $this->path;

		return ob_get_clean();
	}

}