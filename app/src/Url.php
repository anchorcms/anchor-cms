<?php

namespace Anchorcms;

class Url {

	protected $uri;

	public function __construct($request) {
		$this->uri = $request->getUri();
	}

	public function to($path, $query = '') {
		$path = '/' . ltrim($path, '/');

		return (string) (clone $this->uri)->withHost('')->withPath($path)->withQuery($query);
	}

}
