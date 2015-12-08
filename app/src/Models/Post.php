<?php

namespace Models;

class Post extends Model {

	protected $meta;

	public function getDate($format = 'jS M, Y') {
		return \DateTime::createFromFormat('Y-m-d H:i:s', $this->created)->format($format);
	}

	public function getUri() {
		return $this->slug;
		//return $this->getCategory()->getUri() . '/' . $this->slug;
	}

	public function setMeta(array $meta) {
		$this->meta = $meta;
	}

	public function getMeta($key, $default = null) {
		return array_reduce($this->meta, function($default, $row) use($key) {
			return $row->key == $key ? $row->data : $default;
		}, $default);
	}

	public function hasMeta($key) {
		return array_reduce($this->meta, function($default, $row) use($key) {
			return $row->key == $key ? true : $default;
		}, false);
	}

}
