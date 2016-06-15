<?php

namespace Anchorcms\Mappers;

use StdClass;

class Meta extends AbstractMapper {

	protected $primary = 'key';

	protected $name = 'meta';

	public function all() {
		$sql = $this->query();
		$meta = [];

		foreach($this->db->fetchAll($sql) as $row) {
			$meta[$row['key']] = $row['value'];
		}

		return $meta;
	}

	public function key($key, $default = null) {
		$sql = $this->query()->select('value')->where('key = ?');
		$value = $this->db->fetchColumn($sql, [$key]);

		return false === $value ? $default : $value;
	}

}
