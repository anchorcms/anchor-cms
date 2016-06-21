<?php

namespace Anchorcms;

class Config {

	protected $path;

	protected $config;

	public function __construct($path, array $config = []) {
		$this->path = $path;
		$this->config = $config;
	}

	public function load($name) {
		if(false === array_key_exists($name, $this->config)) {
			$path = $this->path . '/' . $name . '.json';

			if(false === is_file($path)) {
				return [];
			}

			$this->config[$name] = json_decode(file_get_contents($path), true);
		}

		return $this->config[$name];
	}

	public function get($path, $default = false) {
		$keys = explode('.', $path);

		// shift the first key which is the file name to load from
		$config = $this->load(array_shift($keys));

		foreach($keys as $key) {
			if(false === array_key_exists($key, $config)) {
				return $default;
			}

			$config = &$config[$key];
		}

		return $config;
	}

}
