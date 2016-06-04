<?php

namespace Session;

class Cookies {

	protected $response;

	protected $cookies;

	public function __construct($request, $response) {
		$this->response = $response;
		$this->extractCookies($request);
	}

	protected function extractCookies($request) {
		$header = $request->getHeaderLine('Cookie');
		$this->cookies = [];

		foreach(array_filter(explode('; ', $header)) as $pair) {
			list($name, $value) = explode('=', $pair, 2);
			$this->cookies[$name] = $value;
		}
	}

	public function has($name) {
		return array_key_exists($name, $this->cookies);
	}

	public function get($name, $default = null) {
		return $this->cookies[$name] ?: $default;
	}

	public function put($name, $value, $expire = 0, $path = '', $domain = '', $secure = false, $httponly = false) {
		$pairs = [
			sprintf('%s=%s', $name, $value),
		];

		if($expire) {
			$time = $expire + time();
			$pairs[] = sprintf('expires=%s', date(DATE_RFC2822, $time));
		}

		if($path) {
			$pairs[] = sprintf('path=%s', $path);
		}

		if($domain) {
			$pairs[] = sprintf('domain=%s', $domain);
		}

		if($secure) {
			$pairs[] = 'secure';
		}

		if($httponly) {
			$pairs[] = 'HttpOnly';
		}

		$this->response->withHeader('Set-Cookie', implode('; ', $pairs));

		return $this;
	}

}
