<?php

class Csrf {

	protected $session;

	public function __construct($session) {
		$this->session = $session;
	}

	public function regenerateToken($length = 32) {
		$bytes = random_bytes($length);
		$token = bin2hex($bytes);

		$this->session->put('csrf_token', $token);
	}

	public function token() {
		if(false === $this->session->has('csrf_token')) {
			$this->regenerateToken();
		}

		return $this->session->get('csrf_token');
	}

	public function verify($str) {
		return hash_equals($this->token(), $str);
	}

}
