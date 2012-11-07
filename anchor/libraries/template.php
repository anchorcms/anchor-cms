<?php

class Template extends View {

	public function __construct($template, $vars = array()) {
		// base path
		$base = PATH . 'themes' . DS . Config::get('meta.theme') . DS;

		// custom article and page templates
		if($template == 'page' or $template == 'article') {
			if($item = Registry::get($template)) {
				if(is_readable($base . $template . '-' . $item->slug . '.php')) {
					$template .= '-' . $item->slug;
				}
			}
		}

		$this->path = $base . $template . '.php';
		$this->vars = array_merge($this->vars, $vars);
	}

}