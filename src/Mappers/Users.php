<?php

namespace Mappers;

class Users extends Mapper {

	protected $primary = 'id';

	protected $name = 'users';

	public function fetchById($id) {
		return $this->where('id', '=', $id)->fetch();
	}

	public function fetchByUsername($name) {
		return $this->where('username', '=', $name)->fetch();
	}

}
