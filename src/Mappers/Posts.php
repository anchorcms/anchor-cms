<?php

namespace Mappers;

class Posts extends Mapper {

	protected $primary = 'id';

	protected $name = 'posts';

	public function id($id) {
		return $this->where('id', '=', $id)->fetch();
	}

	public function slug($slug) {
		return $this->where('slug', '=', $slug)->fetch();
	}

	public function published($per_page, $page = 1) {
		return $this->where('status', '=', 'published')->sort('created', 'desc');
	}

}
