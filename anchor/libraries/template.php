<?php

class Template extends View {

	public function __construct($template, $vars = array()) {
		$this->path = PATH . 'themes' . DS . Config::get('meta.theme') . DS . $template . '.php';
		$this->vars = array_merge($this->vars, $vars);
	}
	
}