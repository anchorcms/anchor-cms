<?php

namespace Controllers;

use ErrorException;
use Content;
use View;

abstract class ThemeAware extends ContainerAware {

	private $path;

	public function setTheme($theme) {
		$theme = $this->meta->key('theme', 'default');
		$path = $this->paths['themes'] . '/' . $theme;

		if(false === is_dir($path)) {
			throw new ErrorException(sprintf('Theme does not exist: %s', $theme));
		}

		$this->view->setPath($path);

		// theme functions
		if(is_file($path . '/functions.php')) {
			require $path . '/functions.php';
		}
	}

	protected function getTemplate(array $names) {
		foreach($names as $name) {
			if($this->view->templateExists($name)) {
				return $name;
			}
		}

		throw new ErrorException(sprintf('Template not found: %s', $name));
	}

	protected function renderTemplate($layout, array $templates, array $vars = []) {
		$template = $this->getTemplate($templates);
		$vars['body'] = $this->view->render($template, $vars);

		$template = $this->getTemplate([$layout]);
		return $this->view->render($template, $vars);
	}

	protected function displayContent($page, $content, $layout, $templates, array $vars = []) {
		$vars['meta'] = $this->meta->all();

		$pages = $this->pages->menu();
		$vars['menu'] = new Content($pages);

		$categories = $this->categories->sort('title')->get();
		$vars['categories'] = new Content($categories);

		$vars['page'] = $page;
		$vars['content'] = $content;

		return $this->renderTemplate($layout, $templates, $vars);
	}

}
