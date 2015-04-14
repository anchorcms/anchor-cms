<?php

class Config {

	protected $path;

	public function __construct($path) {
		$this->path = $path;
	}

	public function get($name) {
		return require $this->path . '/' . $name . '.php';
	}

}
