<?php

class Template extends View {

	public static function create($template, $vars = array()) {
		return new static($template, $vars);
	}

	public function __construct($template, $vars = array()) {
		$this->base = PATH . 'themes' . DS . Config::meta('theme') . DS;
		$this->path = $this->base . $template . EXT;
		$this->vars = array_merge($this->vars, $vars);
	}

	public function set($template) {
		$this->path = $this->base . $template . EXT;
	}

	public function get($template) {
		return $this->path;
	}

	public function exists($template) {
		return is_readable($this->base . $template . EXT);
	}

}