<?php

class Template extends View {

	public function __construct($template, $vars = array()) {
		// custom article and page templates
		if($template == 'page' or $template == 'article') {
			if($item = Registry::get($template)) {
				$template .= '-' . $item->slug;
			}
		}

		$this->path = PATH . 'themes' . DS . Config::get('meta.theme') . DS . $template . '.php';
		$this->vars = array_merge($this->vars, $vars);
	}

}