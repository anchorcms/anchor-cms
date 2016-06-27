<?php

namespace Anchorcms;

use Routing\UriMatcher;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class Kernel
{
    protected $router;

    public function __construct(UriMatcher $router)
    {
        $this->router = $router;
    }

    public function getResponse(ServerRequestInterface $request, ResponseInterface $response, callable $controllerFactory): ResponseInterface
    {
        $path = $request->getUri()->getPath();
        $route = $this->router->match($path);

        if (false === $route) {
            throw new \Routing\RouteNotFoundException('Not Found');
        }

        list($class, $method) = explode('@', $route, 2);

        $instance = $controllerFactory($class);

        $params = $request->getServerParams();
        $verb = array_key_exists('REQUEST_METHOD', $params) ? strtolower($params['REQUEST_METHOD']) : 'get';

        $method = $verb.ucfirst($method);

        return $instance->$method($request, $response, $this->router->getParams());
    }

    public function outputResponse(ResponseInterface $response)
    {
        if (true === headers_sent()) {
            throw new \RuntimeException('headers already sent.');
        }

        header(sprintf('HTTP/%s %s %s',
            $response->getProtocolVersion(),
            $response->getStatusCode(),
            $response->getReasonPhrase()
        ));

        foreach ($response->getHeaders() as $name => $values) {
            foreach ($values as $value) {
                header(sprintf('%s: %s', $name, $value));
            }
        }

        echo $response->getBody();
    }
}
