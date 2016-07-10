<?php

namespace Anchorcms\Middleware;

use Tari\ServerMiddlewareInterface;
use Tari\ServerFrameInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class Debug implements ServerMiddlewareInterface
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function handle(ServerRequestInterface $request, ServerFrameInterface $frame): ResponseInterface
    {
        $response = $frame->next($request);

        $debug = $this->container['view']->render('partials/debug', [
            'profile' => $this->container['db.logger']->queries,
            'memory' => round(memory_get_usage() / 1024 / 1024, 2),
            'execution_time' => round((microtime(true) - $this->container['start_time']) * 1000, 2),
        ]);

        $html = str_replace('<debug></debug>', $debug, (string) $response->getBody());

        $stream = $frame->factory()->createStream($html);

        return $response->withBody($stream);
    }
}
