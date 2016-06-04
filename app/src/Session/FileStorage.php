<?php

namespace Session;

class FileStorage {

	protected $path;

	public function __construct($path) {
		$this->path = $path;
	}

	public function read($id) {
		$path = sprintf('%s/%s.sess', $this->path, $id);

		if(false === is_file($path)) {
			return [];
		}

		$contents = file_get_contents($path);

		return json_decode($contents);
	}

	public function write($id, array $data) {
		$path = sprintf('%s/%s.sess', $this->path, $id);

		if(false === file_put_contents($path, json_encode($data), LOCK_EX)) {
			throw new RuntimeException('Failed to write session file');
		}

		return true;
	}

}
