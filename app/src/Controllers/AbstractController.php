<?php

namespace Controllers;

use Pimple\Container;

abstract class AbstractController {

	protected $container;

	public function setContainer(Container $container) {
		$this->container = $container;
	}

	public function __get($key) {
		return $this->container[$key];
	}

	protected function redirect($uri) {
		return $this->response->withHeader('location', $this->url->to($uri));
	}

	protected function jsonResponse(array $data) {
		$stream = new \Http\Stream();
		$stream->write(json_encode($data));

		return $this->response->withHeader('content-type', 'application/json')->withBody($stream);
	}


}
