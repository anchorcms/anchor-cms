<?php

namespace Events;

class Router extends Event {

	protected $data;

	public function __construct(array $data = []) {
		$this->data = $data;
	}

	public function get($key, $default = null) {
		return array_key_exists($key, $this->data) ? $this->data[$key] : $default;
	}

	public function put($key, $value) {
		$this->data[$key] = $value;
	}

	public function has($key) {
		return array_key_exists($key, $this->data);
	}

	public function append(array $routes) {
		$this->data = array_merge($this->data, $routes);
	}

}
