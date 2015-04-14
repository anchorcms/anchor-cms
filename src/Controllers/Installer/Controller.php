<?php

namespace Controllers\Installer;

abstract class Controller {

	protected $container;

	public function __construct(\Container $container) {
		$this->container = $container;
	}

	public function __get($key) {
		return $this->container[$key];
	}

	protected function renderWith($layout, $template, array $vars = []) {
		$path = __DIR__ . '/../../../app/views/';

		$vars['body'] = $this->render($path . $template, $vars);

		return $this->render($path . $layout, $vars);
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
