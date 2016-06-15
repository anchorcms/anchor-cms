<?php

namespace Anchorcms\Session;

class Cookies {

	protected $cookies;

	public function __construct(array $cookies = null) {
		$this->cookies = null === $cookies ? $_COOKIE : $cookies;
	}

	public function has($name) {
		return array_key_exists($name, $this->cookies);
	}

	public function get($name, $default = null) {
		return $this->cookies[$name] ?: $default;
	}

}
