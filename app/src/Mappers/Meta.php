<?php

namespace Mappers;

use StdClass;

class Meta extends Mapper {

	protected $primary = 'key';

	protected $name = 'meta';

	public function all() {
		$data = new StdClass;

		foreach($this->get() as $row) {
			$data->{$row->key} = $row->value;
		}

		return $data;
	}

	public function key($key, $default = null) {
		$row = $this->select(['value'])->where('key', '=', $key)->fetch();

		if(false === $row) {
			return $default;
		}

		return $row->value;
	}

}
