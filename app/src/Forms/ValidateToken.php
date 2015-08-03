<?php

namespace Forms;

class ValidateToken extends \Validation\AbstractRule {

	protected $token;

	protected $message = '%s is not a valid csrf token';

	public function __construct($token) {
		$this->token = $token;
	}

	public function isValid() {
		return hash_equals($this->getValue(), $this->token);
	}

}
