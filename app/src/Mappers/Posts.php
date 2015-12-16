<?php

namespace Mappers;

class Posts extends Mapper {

	protected $primary = 'id';

	protected $name = 'posts';

	public function published($per_page, $page = 1) {
		return $this->where('status', '=', 'published')->sort('created', 'desc');
	}

	public function filter(array $input) {
		if($input['category']) {
			$this->where('category', '=', $input['category']);
		}

		if($input['status']) {
			$this->where('status', '=', $input['status']);
		}

		if($input['search']) {
			$term = sprintf('%s%s%s', '%', $input['search'], '%');

			$this->whereNested(function($where) use($term) {
				$where('title', 'LIKE', $term)->or('content', 'LIKE', $term);
			});
		}

		return $this;
	}

}
