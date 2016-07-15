<?php

namespace Anchorcms\Middleware;

use Tari\ServerMiddlewareInterface;
use Tari\ServerFrameInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Session\SessionInterface;

class Session implements ServerMiddlewareInterface
{
    protected $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function handle(ServerRequestInterface $request, ServerFrameInterface $frame): ResponseInterface
    {
        $response = $frame->next($request);

        if ($this->session->started()) {
            $this->session->rotate()->close();

            return $response->withHeader('Set-Cookie', $this->session->cookie())
                ->withHeader('Cache-Control', 'no-cache, no-store, must-revalidate');
        }

        return $response;
    }
}
