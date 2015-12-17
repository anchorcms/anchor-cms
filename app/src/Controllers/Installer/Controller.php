<?php

namespace Controllers\Installer;

use Pimple\Container;
use Controllers\ContainerTrait;

abstract class Controller {

	use ContainerTrait;

	public function __construct(Container $container) {
		$this->setContainer($container);
	}

	protected function renderWith($layout, $template, array $vars = []) {
		$vars['body'] = $this->render($this->paths['views'] . '/' . $template, $vars);

		return $this->render($this->paths['views'] . '/' . $layout, $vars);
	}

	protected function render($template, array $vars = []) {
		ob_start();

		extract($vars);

		require $template;

		return ob_get_clean();
	}

	protected function redirect($uri) {
		return $this->response->withHeader('location', $uri);
	}

	protected function asset($file) {
		return $this->url($file);
	}

	protected function url($url) {
		$params = $this->request->getServerParams();
		$base = dirname($params['SCRIPT_NAME']);

		return rtrim($base, '/') . '/' . ltrim($url, '/');
	}

}
