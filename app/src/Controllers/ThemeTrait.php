<?php

namespace Controllers;

trait ThemeTrait {

	public function setTheme($theme) {
		$theme = $this->meta->key('theme', 'default');
		$path = $this->paths['themes'] . '/' . $theme;

		if(false === is_dir($path)) {
			throw new \ErrorException(sprintf('Theme does not exist: %s', $theme));
		}

		$this->view->setPath($path);

		// theme functions
		require $this->paths['app'] . '/functions.php';

		if(is_file($path . '/functions.php')) {
			require $path . '/functions.php';
		}
	}

	/**
	 * Returns the first template that exists
	 *
	 * @param array
	 * @return string
	 */
	protected function getTemplate(array $names) {
		foreach($names as $name) {
			if($this->view->templateExists($name)) {
				return $name;
			}
		}

		throw new \ErrorException(sprintf('Template not found: %s', $name));
	}

	protected function renderTemplate($layout, array $templates, array $vars = []) {
		$vars['uri'] = $this->request->getUri();

		$template = $this->getTemplate($templates);
		$vars['body'] = $this->view->render($template, $vars);

		$template = $this->getTemplate([$layout]);
		return $this->view->render($template, $vars);
	}

	protected function displayContent($page, \ContentIterator $content, $layout, $templates, array $vars = []) {
		$vars['meta'] = $this->meta->all();

		$pages = $this->pages->menu();
		$vars['menu'] = new \ContentIterator($pages);

		$categories = $this->categories->allPublished();
		$vars['categories'] = new \ContentIterator($categories);

		$vars['page'] = $page;
		$vars['content'] = $content;

		return $this->renderTemplate($layout, $templates, $vars);
	}

}
