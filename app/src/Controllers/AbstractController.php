<?php

namespace Controllers;

use Pimple\Container;

abstract class AbstractController {

	protected $container;

	public function setContainer(Container $container) {
		$this->container = $container;
	}

	protected function redirect($uri) {
		return $this->container['http.factory']->createResponse(302, ['location' => $uri], '');
	}

	protected function jsonResponse(array $data) {
		$stream = new \Http\Stream();
		$stream->write(json_encode($data));

		return $this->container['http.factory']->createResponse(200, ['content-type' => 'application/json'], $stream);
	}

}
