<?php

namespace Anchorcms\Middleware;

use Tari\ServerMiddlewareInterface;
use Tari\ServerFrameInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Routing\RouteNotFoundException;

class Kernel implements ServerMiddlewareInterface
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function resolveController($name)
    {
        $name = '\\Anchorcms\\'.implode('\\', array_map('ucfirst', explode('\\', $name)));
        $controller = new $name();
        $controller->setContainer($this->container);

        return $controller;
    }

    public function handle(ServerRequestInterface $request, ServerFrameInterface $frame): ResponseInterface
    {
        $response = $frame->next($request);

        try {
            return $this->container['http.kernel']->getResponse($request, $response, [$this, 'resolveController']);
        } catch (RouteNotFoundException $exception) {
            $vars['title'] = 'Resource Not Found';
            $vars['sitename'] = '';
            $this->container['view']->setExt('html');
            $vars['body'] = $this->container['view']->render('errors/404');
            $this->container['view']->setExt('phtml');
            $html = $this->container['view']->render('layouts/minimal', $vars);
            $stream = $frame->factory()->createStream($html);
            return $frame->factory()->createResponse(404, [], $stream);
        }
    }
}
