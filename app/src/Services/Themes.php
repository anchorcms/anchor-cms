<?php

namespace Services;

class Themes {

	public function __construct($path) {
		$this->path = $path;
	}

	public function getThemes() {
		$themes = [];

		$if = new \FilesystemIterator($this->path, \FilesystemIterator::SKIP_DOTS);

		foreach($if as $file) {
			if($file->isDir()) {
				$key = $file->getBasename();

				$name = ucwords($key);

				$themes[$key] = $name;
			}
		}

		return $themes;
	}

	public function dropdownOptions() {
		return $this->getThemes();
	}

}
