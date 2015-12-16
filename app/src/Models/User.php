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

}
