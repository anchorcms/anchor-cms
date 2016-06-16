<?php

namespace Anchorcms\Controllers;

use Pimple\Container;
use Psr\Http\Message\ResponseInterface;

abstract class AbstractController implements ControllerInterface {

	protected $container;

	public function setContainer(Container $container) {
		$this->container = $container;
	}

	protected function redirect(ResponseInterface $response, string $uri, int $status = 302): ResponseInterface {
		return $response->withStatus($status)->withHeader('Location', $uri);
	}

}
