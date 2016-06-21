<?php

namespace Anchorcms\Mappers;

class Categories extends AbstractMapper {

	protected $primary = 'id';

	protected $name = 'categories';

	public function id($id) {
		return $this->fetchByAttribute('id', $id);
	}

	public function slug($slug) {
		return $this->fetchByAttribute('slug', $slug);
	}

	public function dropdownOptions() {
		$categories = [];

		foreach($this->all() as $category) {
			$categories[$category->id] = $category->title;
		}

		return $categories;
	}

	public function all() {
		$query = $this->query()->orderBy('title', 'ASC');
		$results = $this->db->fetchAll($query);
		$models = [];

		foreach($results as $row) {
			$models[] = (clone $this->prototype)->withAttributes($row);
		}

		return $models;
	}

}
