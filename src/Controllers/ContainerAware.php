<?php

namespace Controllers;

use Container;

abstract class ContainerAware {

	private $container;

	public function setContainer(Container $container) {
		$this->container = $container;
	}

	public function __get($key) {
		return $this->container[$key];
	}

}
