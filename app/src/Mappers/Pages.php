<?php

namespace Mappers;

class Pages extends Mapper {

	protected $primary = 'id';

	protected $name = 'pages';

	public function id($id) {
		return $this->where('id', '=', $id)->fetch();
	}

	public function slug($slug) {
		return $this->where('slug', '=', $slug)->fetch();
	}

	public function menu() {
		return $this->where('status', '=', 'published')
			->where('show_in_menu', '=', '1')
			->sort('menu_order', 'asc')
			->get();
	}

	public function filter(array $input) {
		if($input['status']) {
			$this->where('status', '=', $input['status']);
		}

		return $this;
	}

	public function dropdownOptions() {
		$pages = [0 => 'None'];

		foreach($this->sort('title')->get() as $page) {
			$pages[$page->id] = $page->name;
		}

		return $pages;
	}

}
