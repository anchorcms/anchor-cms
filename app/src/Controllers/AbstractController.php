<?php

namespace Anchorcms\Controllers;

use Pimple\Container;
use Psr\Http\Message\ResponseInterface;

abstract class AbstractController implements ControllerInterface {

	protected $container;

	public function setContainer(Container $container) {
		$this->container = $container;
	}

	protected function renderTemplate(ResponseInterface $response, string $layout, string $template, array $vars = []) {
		$vars['messages'] = $this->container['messages']->render();

		$body = $this->container['mustache']->loadTemplate($template);

		$this->container['mustache']->setPartials([
			'body' => $body->render($vars),
		]);

		$layout = $this->container['mustache']->loadTemplate($layout);
		$output = $layout->render($vars);

		$response->getBody()->write($output);
	}

	protected function redirect(ResponseInterface $response, string $path, int $status = 302): ResponseInterface {
		return $response->withStatus($status)->withHeader('Location', $path);
	}

}
