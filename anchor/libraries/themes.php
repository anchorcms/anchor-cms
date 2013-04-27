<?php

class Themes {

	public static function all() {
		$themes = array();
		$fi = new FilesystemIterator(PATH . 'themes', FilesystemIterator::SKIP_DOTS);

		foreach($fi as $file) {
			if($file->isDir()) {
				$theme = $file->getFilename();

				if($about = static::parse($theme)) {
					$themes[$theme] = $about;
				}
			}
		}

		ksort($themes);

		return $themes;
	}

	public static function parse($theme) {
		$file = PATH . 'themes/' . $theme . '/about.txt';

		if( ! is_readable($file)) {
			return false;
		}

		// read file into a array
		$contents = explode("\n", trim(file_get_contents($file)));
		$about = array();

		foreach(array('name', 'description', 'author', 'site', 'license') as $index => $key) {
			// temp value
			$about[$key] = '';

			// find line if exists
			if( ! isset($contents[$index])) {
				continue;
			}

			$line = $contents[$index];

			// skip if not separated by a colon character
			if(strpos($line, ":") === false) {
				continue;
			}

			$parts = explode(":", $line);

			// remove the key part
			array_shift($parts);

			// in case there was a colon in our value part glue it back together
			$value = implode('', $parts);

			$about[$key] = trim($value);
		}

		return $about;
	}

	public static function templates($theme) {
		$templates = array();
		$fi = new FilesystemIterator(PATH . 'themes/' . $theme, FilesystemIterator::SKIP_DOTS);

		foreach($fi as $file) {
			$ext = pathinfo($file->getFilename(), PATHINFO_EXTENSION);
			$base = $file->getBasename('.' . $ext);

			if($file->isFile() and $ext == 'php') {
				$templates[$base] = $base;
			}
		}

		return $templates;
	}

}