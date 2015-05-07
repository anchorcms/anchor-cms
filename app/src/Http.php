<?php

class Http {

	protected $env;

	protected $index;

	protected $base;

	public function __construct(Collection $env, array $config) {
		$this->env = $env;
		$this->index = array_key_exists('index', $config) ? $this->format($config['index']) : '';
		$this->base = array_key_exists('base', $config) ? $this->format($config['base']) : '';
	}

	protected function format($str) {
		return '/' . trim($str, '/');
	}

	protected function remove($str, $uri) {
		if(strpos($uri, $str) === 0) {
			return substr($uri, strlen($str));
		}

		return $uri;
	}

	protected function removeBase($uri) {
		return $this->base !== '/' ? $this->remove($this->base, $uri) : $uri;
	}

	protected function removeIndex($uri) {
		return $this->index !== '' ? $this->remove($this->index, $uri) : $uri;
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
