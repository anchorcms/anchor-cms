<?php

namespace Services;

class Themes {

	public function __construct($path) {
		$this->path = $path;
	}

	public function getThemes() {
		$if = new \FilesystemIterator($this->path, \FilesystemIterator::SKIP_DOTS);

		foreach($if as $file) {
			if(false === $file->isDir()) continue;

			yield new Themes\Theme($file->getPathname());
		}
	}

}
