<?php

namespace Anchorcms\Controllers;

use Pimple\Container;

abstract class AbstractController implements ControllerInterface {

	protected $container;

	public function setContainer(Container $container) {
		$this->container = $container;
	}

}
