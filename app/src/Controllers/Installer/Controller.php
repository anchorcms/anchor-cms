<?php

namespace Controllers\Installer;

use Controllers\ContainerAware;

abstract class Controller extends ContainerAware {

	public function __construct(\Container $container) {
		$this->setContainer($container);
	}

	protected function renderWith($layout, $template, array $vars = []) {
		$paths = $this->config->get('paths');

		$vars['body'] = $this->render($paths['views'] . '/' . $template, $vars);

		return $this->render($paths['views'] . '/' . $layout, $vars);
	}

	protected function render($template, array $vars = []) {
		ob_start();

		extract($vars);

		require $template;

		return ob_get_clean();
	}

	protected function redirect($uri) {
		header('Location: '.$uri, true, 302);
	}

	protected function asset($file) {
		return $this->url($file);
	}

	protected function url($url) {
		$script = $this->server->get('SCRIPT_NAME');
		$base = dirname($script);

		return rtrim($base, '/') . '/' . ltrim($url, '/');
	}

}
