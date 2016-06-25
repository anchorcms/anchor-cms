<?php

namespace Anchorcms\Middleware;

use Tari\ServerMiddlewareInterface;
use Tari\ServerFrameInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class Session implements ServerMiddlewareInterface
{

    protected $session;

    public function __construct($session)
    {
        $this->session = $session;
    }

    public function handle(ServerRequestInterface $request, ServerFrameInterface $frame): ResponseInterface
    {
        $response = $frame->next($request);

        if ($this->session->started()) {
            $response = $this->session->rotate()->close($response);
        }

        return $response;
    }
}
