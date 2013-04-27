<?php

class Braces {

	public static function compile($path, $vars = array()) {
		$braces = new static($path);

		return $braces->yield($vars);
	}

	public function __construct($path) {
		$this->path = $path;
	}

	public function yield($vars = array()) {
		$content = file_get_contents($this->path);

		$keys = array_map(array($this, 'key'), array_keys($vars));
		$values = array_values($vars);

		return str_replace($keys, $values, $content);
	}

	public function key($var) {
		return '{{' . $var . '}}';
	}

}