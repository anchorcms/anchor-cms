<?php

namespace Controllers;

use ErrorException;
use Content;
use View;

abstract class ThemeAware extends ContainerAware {

	private $path;

	private $ext = '.phtml';

	public function setTheme($theme) {
		$theme = $this->meta->key('theme', 'default');
		$path = $this->paths['themes'] . '/' . $theme;

		if(false === is_dir($path)) {
			throw new ErrorException(sprintf('Theme does not exist: %s', $theme));
		}

		$this->setViewPath($path);

		// theme functions
		if(is_file($path . '/functions.php')) {
			require $path . '/functions.php';
		}
	}

	public function setViewPath($path) {
		$this->path = $path;
	}

	public function getViewPath() {
		return $this->path;
	}

	public function setExt($ext) {
		$this->ext = $ext;
	}

	protected function getTemplate(array $names) {
		foreach($names as $name) {
			$path = $this->path . '/' . $name . $this->ext;

			if(true === is_file($path)) {
				return $path;
			}
		}

		throw new ErrorException(sprintf('Template not found: %s', $path));
	}

	protected function renderTemplate($layout, array $templates, array $vars = []) {
		$template = $this->getTemplate($templates);

		$body = new View($template);
		$vars['body'] = $body->render($vars);

		$template = $this->getTemplate([$layout]);

		$view = new View($template);
		return $view->render($vars);
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
