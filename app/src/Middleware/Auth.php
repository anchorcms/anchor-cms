<?php

namespace Middleware;

use Tari\ServerMiddlewareInterface;
use Tari\ServerFrameInterface;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class Auth implements ServerMiddlewareInterface {

	protected $session;

	public function __construct($session) {
		$this->session = $session;
	}

	public function handle(ServerRequestInterface $request, ServerFrameInterface $frame): ResponseInterface {
		$path = $request->getUri()->getPath();

		if(strpos('/admin', $path) === false) {
			return $frame->next($request);
		}

		if($this->session->has('user')) {
			return $frame->next($request);
		}

		return $frame->factory()->createResponse()->withHeader('Location', '/admin/login');
	}

}
