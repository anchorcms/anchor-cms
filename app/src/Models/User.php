<?php

namespace Models;

class User extends Model {

	public function isPassword($password) {
		return password_verify($password, $this->password);
	}

	public function isActive() {
		return $this->status == 'active';
	}

	public function getName() {
		return $this->real_name;
	}

	public function getEmail() {
		return $this->email;
	}

	public function getEmailEncoded() {
		$encoded = '';

		for($index = 0; $index < strlen($this->email); $index++) {
			$encoded .= '&#'.ord($this->email[$index]).';';
		}

		return $encoded;
	}

}
