<?php

namespace Anchorcms\Middleware;

use Tari\ServerMiddlewareInterface;
use Tari\ServerFrameInterface;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class Auth implements ServerMiddlewareInterface {

	protected $session;

	protected $protected;

	public function __construct($session, array $protected) {
		$this->session = $session;
		$this->protected = $protected;
	}

	public function handle(ServerRequestInterface $request, ServerFrameInterface $frame): ResponseInterface {
		$path = $request->getUri()->getPath();

		foreach($this->protected as $pattern) {
			if(preg_match('#^'.$pattern.'#', $path) && false === $this->session->has('user')) {
				return $frame->factory()->createResponse(302, ['Location' => '/admin/login'], '');
			}
		}

		return $frame->next($request);
	}

}
