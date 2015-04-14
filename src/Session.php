<?php

class Session {

	public function __construct(array $options = []) {
		foreach($options as $key => $value) {
			ini_set('session.'.$key, $value);
		}
	}

	public function start() {
		if(false === $this->started()) {
			session_start();
		}
	}

	public function close() {
		if($this->started()) {
			session_write_close();
		}
	}

	public function started() {
		return session_status() === PHP_SESSION_ACTIVE;
	}

	public function put($key, $value) {
		$_SESSION[$key] = $value;
	}

	public function push($key, $value) {
		if( ! $this->has($key)) {
			$_SESSION[$key] = [];
		}

		$_SESSION[$key][] = $value;
	}

	public function get($key, $default = null) {
		return $this->has($key) ? $_SESSION[$key] : $default;
	}

	public function remove($key) {
		if($this->has($key)) {
			unset($_SESSION[$key]);
		}
	}

	public function has($key) {
		return array_key_exists($key, $_SESSION);
	}

	public function putFlash($key, $value) {
		$this->push('in', $key);
		$this->put('_flash.'.$key, $value);
	}

	public function getFlash($key, $default = null) {
		return $this->get('_flash.'.$key, $default);
	}

	public function rotate() {
		foreach($this->get('out', []) as $key) {
			$this->remove('_flash.'.$key);
		}

		if($this->has('in')) {
			$this->put('out', $this->get('in'));
		}
		else {
			$this->remove('out');
		}

		$this->remove('in');
	}

}
