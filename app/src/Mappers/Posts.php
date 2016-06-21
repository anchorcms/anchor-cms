<?php

namespace Anchorcms\Mappers;

class Posts extends AbstractMapper {

	protected $primary = 'id';

	protected $name = 'posts';

	public function id($id) {
		return $this->fetchByAttribute('id', $id);
	}

	public function slug($slug) {
		return $this->fetchByAttribute('slug', $slug);
	}

}
