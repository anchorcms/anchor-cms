<?php namespace System;

/**
 * Nano
 *
 * Lightweight php framework
 *
 * @package		nano
 * @author		k. wilson
 * @link		http://madebykieron.co.uk
 */

class View {

	public $path, $vars = array();

	public static function make($file, $vars = array()) {
		return new static($file, $vars);
	}

	public function __construct($file, $vars = array()) {
		$this->path = APP . 'views/' . $file . EXT;
		$this->vars = array_merge($this->vars, $vars);
	}

	public function nest($name, $file, $vars = array()) {
		$this->vars[$name] = static::make($file, $vars)->render();
		return $this;
	}

	public function render() {
		ob_start();

		extract($this->vars);

		require $this->path;

		return ob_get_clean();
	}

	public function __toString() {
		return $this->render();
	}

}