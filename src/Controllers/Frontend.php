<?php

namespace Controllers;

abstract class Frontend {

	protected $container;

	public function __construct(\Container $app) {
		$this->container = $app;
		$paths = $this->config->get('paths');
		$theme = $this->meta->key('theme', 'sail');
		$path = $paths['themes'] . '/' . $theme;

		if( ! is_dir($path)) {
			throw new \ErrorException(sprintf('theme does not exist: %s', $theme));
		}

		if(is_file($path . '/functions.php')) {
			require $path . '/functions.php';
		}

		$this->templatePath = $path;
		$this->templateExt = '.phtml';
	}

	public function __get($key) {
		return $this->container[$key];
	}

	protected function displayContent($page, $content, $layout, $templates, array $vars = []) {
		$vars['meta'] = $this->meta->all();

		$pages = $this->pages->menu();
		$vars['menu'] = new \Content($pages);

		$categories = $this->categories->sort('title')->get();
		$vars['categories'] = new \Content($categories);

		$vars['page'] = $page;
		$vars['content'] = $content;

		return $this->renderTemplate($layout, $templates, $vars);
	}

	protected function renderTemplate($layout, $templates, array $vars = []) {
		$template = $this->getValidTemplate($templates);
		$vars['body'] = $this->render($template, $vars);

		return $this->render($this->getValidTemplate([$layout]), $vars);
	}

	protected function getValidTemplate(array $names) {
		foreach($names as $name) {
			$filepath = $this->templatePath . '/' . $name . $this->templateExt;

			if(is_file($filepath)) {
				return $filepath;
			}
		}

		throw new \ErrorException(sprintf('template not found: [%s] [%s] [%s]', realpath($this->templatePath), implode(', ', $names), $this->templateExt));
	}

	protected function render($template, array $vars = []) {
		$this->events->trigger('render', $template, $vars);

		ob_start();

		extract($vars);

		require $template;

		return ob_get_clean();
	}

	public function notFound() {
		http_response_code(404);

		$page = new \StdClass;
		$page->title = 'Not Found';
		$page->html = 'The resource your looking for is not found.';

		$content = new \Content;
		$content->attach($page);

		return $this->displayContent($page, $content, 'layout', ['404', 'page', 'index']);
	}

	public function redirect($uri) {
		$this->events->trigger('redirect', $uri);

		header('Location: '.$uri, true, 302);
	}

}
