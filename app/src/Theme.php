<?php

class Theme {

	protected $view;

	protected $theme;

	protected $paths;

	protected $events;

	protected $layout;

	protected $themeName;

	public function __construct($view, array $paths, $events, $layout = 'layout') {
		$this->view = $view;
		$this->paths = $paths;
		$this->events = $events;
		$this->setLayout($layout);
	}

	/**
	 * Set the layout filename
	 *
	 * @param string
	 */
	public function setLayout($layout) {
		$this->layout = $layout;
	}

	/**
	 * Returns the current theme name
	 *
	 * @return string
	 */
	public function getTheme() {
		return $this->themeName;
	}

	/**
	 * Set the theme folder
	 *
	 * @param string
	 */
	public function setTheme($theme) {
		$this->themeName = $theme;

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

		$this->events->trigger('theme_functions');
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

	/**
	 * Render a theme template and return the html
	 *
	 * @param array
	 * @param array
	 * @return string
	 */
	public function render(array $templates, array $vars = []) {
		$template = $this->getTemplate($templates);
		$vars['body'] = $this->view->render($template, $vars);

		$template = $this->getTemplate([$this->layout]);
		return $this->view->render($template, $vars);
	}

}
