<?php

namespace Anchorcms\Middleware;

use Tari\ServerMiddlewareInterface;
use Tari\ServerFrameInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class RedirectTrailingSlash implements ServerMiddlewareInterface {

	public function handle(ServerRequestInterface $request, ServerFrameInterface $frame): ResponseInterface
    {
		$path = $request->getUri()->getPath();

		// last character is a slash?
		if(substr($path, -1) === '/') {
			return $frame->factory()->createResponse(301, ['Location' => substr($path, 0, -1)]);
		}

        return $frame->next($request);
    }

}
