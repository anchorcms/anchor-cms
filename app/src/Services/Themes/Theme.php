<?php

namespace Services\Themes;

class Theme {

	public function __construct($path) {
		$this->path = $path;
		$this->name = basename($path);
	}

	public function isActive() {
		return true;
	}

	public function getName() {
		return $this->name;
	}

}
