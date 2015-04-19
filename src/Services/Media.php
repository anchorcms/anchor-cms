<?php

namespace Services;

use FilesystemIterator;

class Media {

	public function get() {
		$fi = new FilesystemIterator(__DIR__ . '/../../content', FilesystemIterator::SKIP_DOTS);
		return array_filter((array) $fi, function($file) { return $file->isFile(); });
	}

}
