<?php

class Url {

	protected $uri;

	protected $root;

	public function __construct($request) {
		$this->uri = clone $request->getUri();
		$params = $request->getServerParams();
		$this->root = array_key_exists('SCRIPT_NAME', $params) ? dirname($params['SCRIPT_NAME']) : '';
	}

	public function to($uri, $prepend = null) {
        $this->uri->withQuery('');  //clears old query before outputing new path
		$path = $this->root;

		if($prepend) {
			$path .= '/' . ltrim($prepend, '/');
		}

		$path .= '/' . ltrim($uri, '/');

		return (string) $this->uri->withPath($path);
	}

}
