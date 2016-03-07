<?php

class Config {

	protected $path;

	protected $config;

	public function __construct($path) {
		$this->path = $path;
		$this->config = [];
	}

	public function load($name) {
		if(false === array_key_exists($name, $this->config)) {
			$this->config[$name] = require $this->path . '/' . $name . '.php';
		}

		return $this->config[$name];
	}

	public function get($name) {
		$keys = explode('.', $name);

		$config = $this->load(array_shift($keys));

		foreach($keys as $key) {
			if(array_key_exists($key, $config)) {
				if(is_array($config[$key])) {
					$config &= $config[$key];
				}
				else {
					return $config[$key];
				}
			}
			else {
				throw new InvalidArgumentException(sprintf('Index not found: %s', $key));
			}
		}

		return $config;
	}

}
