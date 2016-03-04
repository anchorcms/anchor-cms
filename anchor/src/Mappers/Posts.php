<?php

namespace Mappers;

class Posts extends Mapper {

	protected $primary = 'id';

	protected $name = 'posts';

	public function filter(array $input) {
		if($input['category']) {
			$this->where('category', '=', $input['category']);
		}

		if($input['status']) {
			$this->where('status', '=', $input['status']);
		}

		if($input['search']) {
			$term = sprintf('%%%s%%', $input['search']);

			$this->whereNested(function($where) use($term) {
				$where('title', 'LIKE', $term)->or('content', 'LIKE', $term);
			});
		}

		return $this;
	}

}
