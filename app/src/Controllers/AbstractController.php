<?php

namespace Controllers;

use Pimple\Container;

abstract class AbstractController {

	protected $container;

	public function setContainer(Container $container) {
		$this->container = $container;
	}

	protected function redirect($uri) {
		return $this->container['middleware.response']->withStatus(302, 'Found')->withHeader('location', $uri);
	}

	protected function jsonResponse(array $data) {
		$stream = new \Http\Stream();
		$stream->write(json_encode($data));

		return $this->container['middleware.response']->withHeader('content-type', 'application/json')->withBody($stream);
	}

}
