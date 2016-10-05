<?php
namespace Anchorcms\Plugins\Debugger\Middleware;

use Anchorcms\View;
use Tari\ServerMiddlewareInterface as Middleware;
use Tari\ServerFrameInterface as Frame;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class Debug implements Middleware
{
    protected $container;

    protected $view;

    public function __construct($container)
    {
        $this->container = $container;
        $this->view = new View(realpath(__DIR__  . '/../views'), 'phtml');
    }

    public function handle(Request $request, Frame $frame): Response
    {
        $response = $frame->next($request);

        $debug = $this->view->render('debug', [
            'profile' => $this->container['db.logger']->queries,
            'memory' => round(memory_get_usage() / 1024 / 1024, 2),
            'execution_time' => round((microtime(true) - $this->container['start_time']) * 1000, 2),
        ]);

        $html = str_replace('<debug></debug>', $debug, (string) $response->getBody());

        $stream = $frame->factory()->createStream($html);

        return $response->withBody($stream);
    }
}
