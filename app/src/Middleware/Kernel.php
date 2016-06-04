<?php

namespace Middleware;

use Tari\ServerMiddlewareInterface;
use Tari\ServerFrameInterface;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class Kernel implements ServerMiddlewareInterface {

	protected $container;

	public function __construct($container) {
		$this->container = $container;
	}

	public function resolveController($name) {
		$name = '\\' . implode('\\', array_map('ucfirst', explode('\\', $name)));
		$controller = new $name;
		$controller->setContainer($this->container);
		return $controller;
	}

	public function handle(ServerRequestInterface $request, ServerFrameInterface $frame): ResponseInterface {
		$response = $frame->next($request);

		$response = $this->container['http.kernel']->getResponse($request, $response, [$this, 'resolveController']);

		if( ! $response instanceof Psr\Http\Message\ResponseInterface) {
			$response = $frame->factory()->createResponse(200, [], $response);
		}

		return $response;
	}

}
