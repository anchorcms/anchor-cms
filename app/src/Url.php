<?php

namespace Anchorcms;

use Psr\Http\Message\{
	ServerRequestInterface,
	UriInterface
};

class Url {

	protected $host;

	protected $scheme;

	protected $uri;

	public function __construct(ServerRequestInterface $request, UriInterface $uri) {
		$this->uri = $uri;
		$params = $request->getServerParams();
		$this->host = empty($params['HTTP_HOST']) ? 'localhost' : $params['HTTP_HOST'];
		$this->scheme = empty($params['HTTPS']) ? 'http' : 'https';
	}

	public function to(string $path): UriInterface {
		$path = '/' . ltrim($path, '/');
		return (clone $this->uri)->withScheme($this->scheme)->withHost($this->host)->withPath($path);
	}

}
