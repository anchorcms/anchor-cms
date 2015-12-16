<?php

namespace Controllers;

use Pimple\Container;

trait ContainerTrait {

	private $container;

	public function setContainer(Container $container) {
		$this->container = $container;
	}

	public function __get($key) {
		return $this->container[$key];
	}

}
