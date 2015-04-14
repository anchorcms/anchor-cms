<?php

class Arr implements ArrayAccess {

	protected $data;

	public function __construct(array $data) {
		$this->data = $data;
	}

	public function offsetExists($key) {
		return array_key_exists($key, $this->data);
	}

	public function offsetGet($key) {
		return $this->data[$key];
	}

	public function offsetSet($key, $value) {
		$this->data[$key] = $value;
	}

	public function offsetUnset($key) {
		unset($this->data[$key]);
	}

	public function get($key, $default = null) {
		return $this->offsetExists($key) ? $this->offsetGet($key) : $default;
	}

}
