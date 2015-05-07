<?php

namespace Mappers;

class Categories extends Mapper {

	protected $primary = 'id';

	protected $name = 'categories';

	public function id($id) {
		return $this->where('id', '=', $id)->fetch();
	}

	public function slug($slug) {
		return $this->where('slug', '=', $slug)->fetch();
	}

}
