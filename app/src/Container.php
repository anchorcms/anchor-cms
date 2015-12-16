<?php

class Container extends Pimple\Container {

	public function __get($key) {
		return $this[$key];
	}

}
