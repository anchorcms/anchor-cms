<?php

namespace Mappers;

class Users extends AbstractMapper {

	protected $primary = 'id';

	protected $name = 'users';

	public function fetchById($id) {
		return $this->where('id', '=', $id)->fetch();
	}

	public function fetchByUsername($name) {
		return $this->where('username', '=', $name)->fetch();
	}

	public function fetchByEmail($email) {
		return $this->where('email', '=', $email)->fetch();
	}

}
