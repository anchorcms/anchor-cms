<?php

namespace Anchorcms;

use Psr\Http\Message\UriInterface;

class Url
{
    protected $host;

    protected $scheme;

    protected $uri;

    public function __construct(array $serverParams, UriInterface $uri)
    {
        $this->uri = $uri;
        $this->host = empty($serverParams['HTTP_HOST']) ? 'localhost' : $serverParams['HTTP_HOST'];
        $this->scheme = empty($serverParams['HTTPS']) ? 'http' : 'https';
    }

    public function to(string $path): UriInterface
    {
        $path = '/'.ltrim($path, '/');

        return (clone $this->uri)->withScheme($this->scheme)->withHost($this->host)->withPath($path);
    }
}
