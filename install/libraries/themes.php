<?php

class Themes {

	public static function all() {
		$themes = array();

		foreach(glob(PATH . 'themes/*') as $folder) {
			$theme = basename($folder);

			if($about = static::parse($theme)) {
				$themes[$theme] = $about;
			}
		}

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

}