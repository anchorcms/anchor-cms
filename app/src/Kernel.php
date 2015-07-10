<?php


use Routing\UriMatcher;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

use Http\Response;
use Http\Stream;

class Kernel {

	protected $request;

	protected $router;

	protected $container;

	public function __construct(ServerRequestInterface $request, UriMatcher $router, Container $container) {
		$this->request = $request;
		$this->router = $router;
		$this->container = $container;
	}

	protected function removeScriptName($uri, $script) {
		$parts = explode('/', trim($script, '/'));

		foreach($parts as $part) {
			$segment = '/'.$part;

			if(strpos($uri, $segment) === 0) {
				$uri = substr($uri, strlen($segment));
			}
		}

		return $uri ?: '/';
	}

	protected function formatClassName($str) {
		return implode('\\', array_map('ucfirst', explode('\\', $str)));
	}

	public function redirectTrailingSlash() {
		$path = $this->request->getUri()->getPath();

		if($path != '/' && substr($path, -1) == '/') {
			header('Location: ' . $this->request->getUri()->withPath(rtrim($path, '/')), true, 301);
			exit;
		}
	}

	public function getResponse() {
		$params = $this->request->getServerParams();
		$script = array_key_exists('SCRIPT_NAME', $params) ? $params['SCRIPT_NAME'] : '';
		$path = $this->removeScriptName($this->request->getUri()->getPath(), $script);

		$route = $this->router->match($path);

		if(false === $route) {
			throw new ErrorException(sprintf('no route matched: %s', $this->request->getUri()->getPath()));
		}

		$this->request->withAttributes($this->router->getParams());

		if($route instanceof Closure) {
			$output = $route($this->request);

			return $this->createResponse($output);
		}

		return $this->handleController($route);
	}

	protected function handleController($route) {
		list($class, $method) = explode('@', $route);

		$name = $this->formatClassName($class);
		$instance = new $name($this->container);

		if(method_exists($instance, 'before')) {
			$output = $instance->before($this->request);

			if($output) return $this->createResponse($output);
		}

		$params = $this->request->getServerParams();
		$verb = array_key_exists('REQUEST_METHOD', $params) ? strtolower($params['REQUEST_METHOD']) : 'get';

		$method = $verb . ucfirst($method);
		$output = $instance->$method($this->request);

		$response = $this->createResponse($output);

		if(method_exists($instance, 'after')) {
			call_user_func([$instance, 'after'], $response);
		}

		return $response;
	}

	public function createResponse($result) {
		if($result instanceof ResponseInterface) {
			return $result;
		}

		$stream = new Stream();
		$stream->write((string) $result);

		$response = new Response();
		$response->withProtocolVersion($this->request->getProtocolVersion())
			->withHeader('Content-Type', 'text/html; charset=UTF-8')
			->withStatus(200, 'OK')
			->withBody($stream);

		return $response;
	}

	public function outputResponse(ResponseInterface $response) {
		if(true === headers_sent()) {
			throw new ErrorException('headers already sent.');
		}

		header(sprintf('%s %s %s',
			$response->getProtocolVersion(),
			$response->getStatusCode(),
			$response->getReasonPhrase()
		));

		foreach($response->getHeaders() as $name => $values) {
			foreach($values as $value) {
				header(sprintf('%s: %s', $name, $value));
			}
		}

		echo $response->getBody();
	}

}
