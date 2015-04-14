<?php

class Http {

	protected $env;

	protected $index;

	protected $base;

	public function __construct(Arr $env, array $config) {
		$this->env = $env;
		$this->index = $config['index'];
		$this->base = $config['base'];
	}

	protected function remove($str, $uri) {
		if(strpos($uri, $str) === false) {
			return $uri;
		}

		return substr($uri, strlen($str));
	}

	protected function removeBase($uri) {
		return $this->base !== '/' ? $this->remove($this->base, $uri) : $uri;
	}

	protected function removeIndex($uri) {
		return strlen($this->index) ? $this->remove($this->index, $uri) : $uri;
	}

	protected function removeQuery($uri) {
		return strpos($uri, '?') === false ? $uri : strstr($uri, '?', true);
	}

	public function getUri() {
		$uri = $this->env->get('REQUEST_URI', '/');
		$uri = $this->removeBase($uri);
		$uri = $this->removeIndex($uri);
		$uri = $this->removeQuery($uri);

		return $uri ?: '/';
	}

	public function getMethod() {
		return $this->env->get('REQUEST_METHOD', 'GET');
	}

}
