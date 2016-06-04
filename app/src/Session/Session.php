<?php

namespace Session;

class Session {

	protected $cookies;

	protected $storage;

	protected $data;

	protected $id;

	protected $name;

	protected $started;

	public function __construct($name, $cookies, $storage) {
		$this->name = $name;
		$this->cookies = $cookies;
		$this->storage = $storage;
		$this->data = [];
		$this->started = false;
	}

	protected function generateId() {
		return bin2hex(random_bytes(32));
	}

	public function start() {
		if($this->cookies->has($this->name)) {
			$this->id = $this->cookies->get($this->name);
		}
		else {
			$this->id = $this->generateId();
		}

		$this->data = $this->storage->read($this->id);

		$this->started = true;
	}

	public function started() {
		return $this->started;
	}

	public function close() {
		if( ! $this->started) {
			throw new RuntimeException('Session has not been started');
		}

		$this->cookies->put($this->name, $this->id);
		$this->storage->write($this->id, $this->data);
	}

	public function has($key) {
		return array_key_exists($key, $this->data);
	}

	public function get($key, $default = null) {
		return $this->data[$key] ?: $default;
	}

	public function put($key, $value) {
		$this->data[$key] = $value;

		return $this;
	}

	public function push($key, $value) {
		$this->data[$key][] = $value;

		return $this;
	}

	public function rotate() {
		$this->data['_stash_out'] = $this->data['_stash_in'] ?? [];

		return $this;
	}

	public function getStash($key, $default = null) {
		return $this->data['_stash_out'][$key] ?? $default;
	}

	public function putStash($key, $value) {
		$this->data['_stash_in'][$key] = $value;

		return $this;
	}

}
