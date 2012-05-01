<?php defined('IN_CMS') or die('No direct access allowed.');

class Themes {
	
	public static function list_all() {
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

		if(file_exists($file) === false) {
			return false;
		}

		// read file into a array
		$contents = explode("\n", trim(file_get_contents($file)));
		$about = array();

		foreach(array('name', 'description', 'author', 'site', 'license') as $index => $key) {
			// temp value
			$about[$key] = '';

			// find line if exists
			if(!isset($contents[$index])) {
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