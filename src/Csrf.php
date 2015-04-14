<?php

class Csrf {

	protected $secret;

	protected $len = 16;

	protected $algo = 'sha256';

	public function __construct($secret) {
		$this->secret = $secret;
	}

	protected function sign($salt) {
		return hash_hmac($this->algo, $salt, $this->secret);
	}

	public function token() {
		$pool = str_shuffle(str_repeat('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', 10));
		$salt = substr($pool, 0, $this->len);

		return $salt.$this->sign($salt);
	}

	public function verify($str) {
		$salt = substr($str, 0, $this->len);
		$hash = substr($str, $this->len);

		return $hash === $this->sign($salt);
	}

}
