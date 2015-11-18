<?php

class Template extends View {

	public function __construct($template, $vars = array()) {
		// base path
		$base = PATH . 'themes' . DS . Config::meta('theme') . DS;

		// custom article and page templates
		if($template == 'page' or $template == 'article') {
			if($item = Registry::get($template)) {
				if(is_readable($base . $template . '-' . $item->slug . EXT)) {
					$template .= '-' . $item->slug;
				} elseif (is_readable($base . $template . 's/' . $template . '-' . $item->slug . EXT)) {
					$template .= 's/' . $template . '-' . $item->slug;
				} elseif (is_readable($base . $item->pagetype . EXT)) {
					$template = $item->pagetype;
					if (is_readable($base . $item->pagetype . '-' . $item->slug . EXT)) {
						$template .= '-' . $item->slug;
					}
				}
			}
		}

		if($template == 'posts') {
			if($item = Registry::get('post_category')) {
				if(is_readable($base . 'category-' . $item->slug . EXT)) {
					$template = 'category';
					$template .= '-' . $item->slug;
				}
			}
		}

		$this->path = $base . $template . EXT;
		$this->vars = array_merge($this->vars, $vars);
	}

	public function __toString() {
		return $this->render();
	}

	public function exists() {
		return file_exists($this->path);
	}
}
